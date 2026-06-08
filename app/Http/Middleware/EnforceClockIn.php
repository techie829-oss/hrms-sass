<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        // 1. Skip entirely for logout and central domain requests
        if ($request->is('logout') || $request->routeIs('logout')) {
            return $next($request);
        }

        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        // 2. Only proceed if we are in a tenant context and the employees table exists
        $tenant = function_exists('saas_tenant') ? saas_tenant() : null;
        if (!$tenant || !Schema::hasTable('employees')) {
            return $next($request);
        }

        // 3. If has no employee profile, continue
        if (!$user->employee) {
            return $next($request);
        }

        // 4. If the Attendance module is disabled for this tenant, bypass entirely
        $attendanceEnabled = DB::table('tenant_modules')
            ->join('modules', 'modules.id', '=', 'tenant_modules.module_id')
            ->where('tenant_modules.tenant_id', $tenant->id)
            ->where('modules.slug', 'attendance')
            ->where('tenant_modules.enabled', true)
            ->exists();

        if (!$attendanceEnabled) {
            return $next($request);
        }

        // 5. Fetch the company-level policy enforce clock-in setting
        $policy = AttendancePolicy::where('is_active', true)->first();
        $companyEnforced = $policy ? (bool)$policy->enforce_clockin : false;

        // 4. Resolve Enforcement (0=Inherit, 1=Force, 2=Exempt)
        $enforcement = 0; // Default to Inherit

        // Priority 1: Employee Level
        $empEnf = AttendanceEmployeeEnforcement::where('employee_id', $user->employee->id)->first();
        if ($empEnf && $empEnf->enforce_kiosk != 0) {
            $enforcement = $empEnf->enforce_kiosk;
        } else {
            // Priority 2: Role Level
            $roleIds = $user->roles()->pluck('id')->toArray();
            $roleEnf = AttendanceRoleEnforcement::whereIn('role_id', $roleIds)
                ->where('enforce_kiosk', '!=', 0)
                ->first(); // Get the first explicit enforcement found for any user role

            if ($roleEnf) {
                $enforcement = $roleEnf->enforce_kiosk;
            } else if ($policy) {
                // Priority 3: Company Level
                $enforcement = $companyEnforced ? 1 : 2;
            }
        }

        // 5. If Exempt (2), allow access
        if ($enforcement == 2) {
            return $next($request);
        }

        // 6. If Forced (1), verify if they have clocked in today
        if ($enforcement == 1) {
            $today = Carbon::today();
            $hasClockedIn = AttendanceLog::where('employee_id', $user->employee->id)
                ->whereDate('date', $today)
                ->whereNotNull('check_in')
                ->exists();

            if (!$hasClockedIn) {
                // List of allowed routes
                $allowedRoutes = [
                    'attendance.kiosk',
                    'attendance.clock-in',
                    'logout',
                    'tenant.dashboard', // Allow dashboard to show the widget
                ];

                $currentRouteName = $request->route() ? $request->route()->getName() : null;

                // Also allow logout and the kiosk routes, otherwise redirect
                if (!in_array($currentRouteName, $allowedRoutes) && !$request->is('logout') && !$request->is('attendance/kiosk') && !$request->is('attendance/clock-in')) {
                    // Only redirect if NOT on dashboard (to prevent infinite loop if dashboard shows widget)
                    if ($currentRouteName !== 'tenant.dashboard') {
                        return redirect()->route('attendance.kiosk')->with('error', 'Daily Clock-In is mandatory for your account. Please Clock In to access HRMS features.');
                    }
                }
            }
        }

        return $next($request);
    }
}
