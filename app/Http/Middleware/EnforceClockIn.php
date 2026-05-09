<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\AttendancePolicy;
use App\Modules\Attendance\Models\AttendanceRoleEnforcement;
use App\Modules\Attendance\Models\AttendanceEmployeeEnforcement;

class EnforceClockIn
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. If not authenticated or has no employee profile, continue
        if (!$user || !$user->employee) {
            return $next($request);
        }

        // 2. If the Attendance module is disabled for this tenant, bypass entirely
        $tenant = function_exists('saas_tenant') ? saas_tenant() : null;
        if ($tenant) {
            $attendanceEnabled = DB::table('tenant_modules')
                ->join('modules', 'modules.id', '=', 'tenant_modules.module_id')
                ->where('tenant_modules.tenant_id', $tenant->id)
                ->where('modules.slug', 'attendance')
                ->where('tenant_modules.enabled', true)
                ->exists();
            if (!$attendanceEnabled) {
                return $next($request);
            }
        }

        // 3. Fetch the company-level policy enforce clock-in setting (from the default active AttendancePolicy)
        $policy = AttendancePolicy::where('is_default', true)
            ->where('is_active', true)
            ->first();
            
        $companyEnforced = $policy ? (bool)$policy->enforce_clockin : false;

        // 4. Employee-level override check (Explicit Exclusion or Force)
        $employeeEnforcement = AttendanceEmployeeEnforcement::where('employee_id', $user->employee->id)->first();

        // If employee has an explicit bypass/exclusion (checkin_required set to false): EXCLUDED
        if ($employeeEnforcement && $employeeEnforcement->checkin_required === false) {
            return $next($request);
        }

        // 5. Determine if check-in is required
        $checkInRequired = false;

        if ($employeeEnforcement && $employeeEnforcement->checkin_required === true) {
            // Explicitly forced at employee level
            $checkInRequired = true;
        } else {
            // Inherit from either company-level or role-level settings
            if ($companyEnforced) {
                $checkInRequired = true;
            } else {
                // Check if any of their active Spatie roles require check-in
                $userRoleNames = $user->roles()->pluck('name')->toArray();
                
                $checkInRequired = AttendanceRoleEnforcement::whereIn('role_name', $userRoleNames)
                    ->where('checkin_required', true)
                    ->exists();
            }
        }

        // 6. If check-in is required, verify if they have clocked in today
        if ($checkInRequired) {
            $today = Carbon::today();
            $hasClockedIn = AttendanceLog::where('employee_id', $user->employee->id)
                ->whereDate('date', $today)
                ->whereNotNull('check_in')
                ->exists();

            if (!$hasClockedIn) {
                // List of allowed route names or paths
                $allowedRoutes = [
                    'attendance.kiosk',
                    'attendance.clock-in',
                    'logout',
                ];

                $currentRouteName = $request->route() ? $request->route()->getName() : null;

                // Also allow logout and the kiosk routes, otherwise redirect
                if (!in_array($currentRouteName, $allowedRoutes) && !$request->is('logout') && !$request->is('attendance/kiosk') && !$request->is('attendance/clock-in')) {
                    return redirect()->route('attendance.kiosk')->with('error', 'Daily Clock-In is mandatory for your account. Please Clock In to access HRMS features.');
                }
            }
        }

        return $next($request);
    }
}
