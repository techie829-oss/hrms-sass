<?php

namespace App\Modules\Attendance\Controllers;

use App\Core\BaseController;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\AttendanceShift;
use App\Modules\Attendance\Models\AttendancePolicy;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use App\Modules\HR\Models\Employee;
use App\Modules\Attendance\Models\AttendanceRoleEnforcement;
use App\Modules\Attendance\Models\AttendanceEmployeeEnforcement;
use App\Modules\Attendance\Services\AttendanceSummaryService;

class ClockController extends BaseController
{
    /**
     * Show the Kiosk view.
     */
    public function kiosk()
    {
        $user = auth()->user();
        $employee = $user->employee;
        $today = Carbon::today();

        $todayLog = null;
        $shift = null;

        if ($employee) {
            $todayLog = AttendanceLog::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->latest()
                ->first();

            $shift = $employee->attendanceShift ?: AttendanceShift::where('is_active', true)
                ->where('is_default', true)
                ->first();
        }

        // Recent 5 days attendance for mini-history
        $recentLogs = $employee
            ? AttendanceLog::where('employee_id', $employee->id)
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get()
            : collect();

        // Fetch effective policy
        $policy = $this->getEffectivePolicy();

        // Check if Multi-Clocking is enabled for this employee
        $isMultiEnabled = $employee ? $this->isMultiClockingEnabled($employee, $policy) : false;

        // Check if Kiosk is even enabled for this policy
        if ($policy && !$policy->is_kiosk_enabled) {
            return redirect()->route('attendance.index')->with('error', 'Kiosk attendance is currently disabled by admin.');
        }

        return view('attendance::kiosk', compact('user', 'employee', 'todayLog', 'shift', 'recentLogs', 'policy', 'isMultiEnabled'));
    }

    /**
     * Handle Clock In.
     */
    public function clockIn(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;
        
        $policy = $this->getEffectivePolicy();

        $request->validate([
            'latitude' => ($policy && $policy->kiosk_require_location) ? 'required' : 'nullable',
            'longitude' => ($policy && $policy->kiosk_require_location) ? 'required' : 'nullable',
            'photo' => ($policy && $policy->kiosk_require_photo) ? 'required' : 'nullable',
        ], [
            'latitude.required' => 'Location access is mandatory.',
            'longitude.required' => 'Location access is mandatory.',
            'photo.required' => 'Photo capture is mandatory.',
        ]);

        if (!$employee) {
            return back()->with('error', 'Employee record not found for your user account.');
        }

        $now = Carbon::now();
        $today = Carbon::today();

        // Check for existing records today
        $existing = AttendanceLog::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->latest()
            ->first();

        $isMultiEnabled = $this->isMultiClockingEnabled($employee, $policy);

        if ($existing) {
            // If multi-clocking is disabled, block further check-ins
            if (!$isMultiEnabled) {
                return back()->with('error', 'You have already clocked in today.');
            }

            // If multi-clocking is enabled, only allow if the last session is closed
            if ($existing->check_in && !$existing->check_out) {
                return back()->with('error', 'You are already clocked in. Please clock out first.');
            }
        }

        // Find Shift: Check employee assignment first, then default
        $shift = $employee->attendanceShift ?: AttendanceShift::where('is_active', true)
            ->where('is_default', true)
            ->first();

        $targetStartTime = null;
        $graceMinutes = 0;
        $isLate = false;
        $lateMinutes = 0;

        if ($shift) {
            $targetStartTime = $shift->start_time;
            $graceMinutes = $shift->grace_minutes;
        } elseif ($policy && $policy->default_start_time) {
            $targetStartTime = $policy->default_start_time;
            $graceMinutes = $policy->late_mark_after_minutes ?? 0;
        }

        if ($targetStartTime) {
            $startTime = Carbon::parse($targetStartTime)->setDate($today->year, $today->month, $today->day);
            $graceTime = $startTime->copy()->addMinutes($graceMinutes);

            if ($now->greaterThan($graceTime)) {
                $isLate = true;
                $lateMinutes = $now->diffInMinutes($startTime);
            }
        }

        // Save check-in photo
        $photoPath = $this->savePhoto($request->input('photo'), $employee->id, 'checkin');

        AttendanceLog::create([
            'tenant_id' => saas_tenant('id'),
            'employee_id' => $employee->id,
            'attendance_shift_id' => $shift?->id,
            'date' => $today,
            'check_in' => $now,
            'status' => $isLate ? 'late' : 'present',
            'is_late' => $isLate,
            'late_minutes' => $lateMinutes,
            'check_in_ip' => $request->ip(),
            'check_in_lat' => $request->input('latitude'),
            'check_in_lng' => $request->input('longitude'),
            'check_in_photo' => $photoPath,
            'remarks' => $request->input('device_info'),
        ]);

        $msg = 'Clocked in at ' . $now->format('h:i A');
        if ($isLate) {
            $msg .= ' — Late by ' . $lateMinutes . ' minutes';
        }

        // Recompute daily summary
        (new AttendanceSummaryService())->recompute($employee->id, saas_tenant('id'), $today);

        return back()->with('success', $msg);
    }

    /**
     * Handle Clock Out.
     */
    public function clockOut(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        $policy = $this->getEffectivePolicy();

        $request->validate([
            'latitude' => ($policy && $policy->kiosk_require_location) ? 'required' : 'nullable',
            'longitude' => ($policy && $policy->kiosk_require_location) ? 'required' : 'nullable',
            'photo' => ($policy && $policy->kiosk_require_photo) ? 'required' : 'nullable',
        ], [
            'latitude.required' => 'Location access is mandatory.',
            'longitude.required' => 'Location access is mandatory.',
            'photo.required' => 'Photo capture is mandatory.',
        ]);

        if (!$employee) {
            return back()->with('error', 'Employee record not found.');
        }

        $today = Carbon::today();

        $log = AttendanceLog::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->latest()
            ->first();

        if (!$log) {
            return back()->with('error', 'No active clock-in found for today.');
        }

        $checkOutTime = Carbon::now();
        $checkInTime = Carbon::parse($log->check_in);
        
        // Calculate worked hours
        $diffInMinutes = $checkInTime->diffInMinutes($checkOutTime);
        $workedHours = round($diffInMinutes / 60, 2);

        $shift = $employee->attendanceShift ?: AttendanceShift::where('is_active', true)
            ->where('is_default', true)
            ->first();

        $targetEndTime = null;
        $overtimeMinutes = 0;
        if ($shift) {
            $targetEndTime = $shift->end_time;
        } elseif ($policy && $policy->default_end_time) {
            $targetEndTime = $policy->default_end_time;
        }

        if ($targetEndTime) {
            $endTime = Carbon::parse($targetEndTime)->setDate($today->year, $today->month, $today->day);
            
            if ($checkOutTime->greaterThan($endTime)) {
                $overtimeMinutes = $checkOutTime->diffInMinutes($endTime);
            }
        }

        // Save check-out photo
        $photoPath = $this->savePhoto($request->input('photo'), $employee->id, 'checkout');

        // Append device info to remarks
        $existingRemarks = $log->remarks ?? '';
        $deviceInfo = $request->input('device_info');
        $updatedRemarks = $existingRemarks;
        if ($deviceInfo) {
            $updatedRemarks = trim($existingRemarks . ' | OUT: ' . $deviceInfo);
        }

        $log->update([
            'check_out' => $checkOutTime,
            'worked_hours' => $workedHours,
            'overtime_minutes' => $overtimeMinutes,
            'check_out_ip' => $request->ip(),
            'check_out_lat' => $request->input('latitude'),
            'check_out_lng' => $request->input('longitude'),
            'check_out_photo' => $photoPath,
            'remarks' => $updatedRemarks,
        ]);

        // Recompute daily summary
        (new AttendanceSummaryService())->recompute($employee->id, saas_tenant('id'), $today);

        $msg = 'Clocked out at ' . $checkOutTime->format('h:i A') . '. Worked ' . $workedHours . ' hours.';
        if ($overtimeMinutes > 0) {
            $msg .= ' (Overtime: ' . $overtimeMinutes . ' mins)';
        }

        return back()->with('success', $msg);
    }

    /**
     * Save a base64-encoded photo to storage.
     */
    private function savePhoto(?string $base64, int $employeeId, string $type): ?string
    {
        if (!$base64 || !str_contains($base64, 'base64,')) {
            return null;
        }

        $imageData = base64_decode(explode(',', $base64)[1]);
        $filename = 'attendance-photos/' . $employeeId . '_' . $type . '_' . now()->format('Ymd_His') . '.jpg';
        Storage::disk('public')->put($filename, $imageData);

        return $filename;
    }

    /**
     * Show Attendance modular Settings.
     */
    public function settings()
    {
        $policy = $this->getEffectivePolicy();
        
        $roles = Role::where('tenant_id', saas_tenant('id'))->get();
        
        // Fetch existing enforcements as integers (0=Inherit, 1=Force, 2=Exempt)
        $roleEnforcements = AttendanceRoleEnforcement::where('tenant_id', saas_tenant('id'))
            ->pluck('enforce_kiosk', 'role_id')
            ->map(fn($v) => (string)$v)
            ->toArray();
            
        // Fetch multi-clocking enforcements as strings ('0'=Inherit, '1'=Allowed, '2'=Disallowed)
        $multiRoleEnforcements = AttendanceRoleEnforcement::where('tenant_id', saas_tenant('id'))
            ->pluck('multi_clocking', 'role_id')
            ->map(fn($v) => (string)$v)
            ->toArray();

        $employees = Employee::all();
        
        $employeeEnforcements = AttendanceEmployeeEnforcement::where('tenant_id', saas_tenant('id'))
            ->pluck('enforce_kiosk', 'employee_id')
            ->map(fn($v) => (string)$v)
            ->toArray();
            
        $employeeMultiEnforcements = AttendanceEmployeeEnforcement::where('tenant_id', saas_tenant('id'))
            ->pluck('multi_clocking', 'employee_id')
            ->map(fn($v) => (string)$v)
            ->toArray();

        $employeeFlexible = AttendanceEmployeeEnforcement::where('tenant_id', saas_tenant('id'))
            ->pluck('is_flexible', 'employee_id')
            ->toArray();

        $shifts = AttendanceShift::where('tenant_id', saas_tenant('id'))->orderBy('name')->get();

        return view('attendance::settings', compact(
            'policy', 'roles', 'roleEnforcements', 'multiRoleEnforcements',
            'employees', 'employeeEnforcements', 'employeeMultiEnforcements',
            'employeeFlexible', 'shifts'
        ));
    }

    /**
     * Save Attendance modular Settings.
     */
    public function saveSettings(Request $request)
    {
        $policy = $this->getEffectivePolicy();
        if ($policy) {
            $policy->update([
                // Enforcement
                'enforce_clockin'    => $request->has('enforce_clockin'),
                'multi_clocking'     => (int)$request->input('multi_clocking_policy', 0),
                // Office Timing
                'auto_checkout'      => $request->has('auto_checkout'),
                'auto_checkout_time' => $request->input('auto_checkout_time'),
                'default_start_time' => $request->input('default_start_time'),
                'default_end_time'   => $request->input('default_end_time'),
                // Thresholds
                'min_hours_full_day'          => (int)$request->input('min_hours_full_day', 8),
                'min_hours_half_day'          => (int)$request->input('min_hours_half_day', 4),
                'late_mark_after_minutes'     => (int)$request->input('late_mark_after_minutes', 15),
                'early_leave_before_minutes'  => (int)$request->input('early_leave_before_minutes', 30),
                'max_late_allowed_per_month'  => (int)$request->input('max_late_allowed_per_month', 3),
                'auto_deduct_leave'           => $request->has('auto_deduct_leave'),
            ]);
        }

        // Save Role Enforcements (Scoped to Tenant)
        AttendanceRoleEnforcement::where('tenant_id', saas_tenant('id'))->delete();
        $rolesData = $request->input('roles', []);
        $multiRolesData = $request->input('multi_roles', []);
        
        foreach ($rolesData as $roleId => $required) {
            $multiState = $multiRolesData[$roleId] ?? '0';
            
            if ($required !== '0' || $multiState !== '0') {
                AttendanceRoleEnforcement::create([
                    'tenant_id' => saas_tenant('id'),
                    'role_id' => $roleId,
                    'enforce_kiosk' => (int)$required,
                    'multi_clocking' => (int)$multiState,
                ]);
            }
        }

        // Save Employee Enforcements (Scoped to Tenant)
        AttendanceEmployeeEnforcement::where('tenant_id', saas_tenant('id'))->delete();
        $employeesData      = $request->input('employees', []);
        $multiEmployeesData = $request->input('multi_employees', []);
        $flexibleEmployeesData = $request->input('flexible_employees', []);

        foreach ($employeesData as $employeeId => $state) {
            $multiState    = $multiEmployeesData[$employeeId] ?? '0';
            $isFlexible    = isset($flexibleEmployeesData[$employeeId]);

            if ($state !== '0' || $multiState !== '0' || $isFlexible) {
                AttendanceEmployeeEnforcement::updateOrCreate(
                    ['tenant_id' => saas_tenant('id'), 'employee_id' => $employeeId],
                    [
                        'enforce_kiosk'  => (int)$state,
                        'multi_clocking' => (int)$multiState,
                        'is_flexible'    => $isFlexible,
                    ]
                );
            } else {
                // Remove record if everything is default
                AttendanceEmployeeEnforcement::where('tenant_id', saas_tenant('id'))
                    ->where('employee_id', $employeeId)
                    ->delete();
            }
        }

        return redirect()->route('attendance.settings')->with('success', 'Attendance Clock-In Enforcement settings saved successfully.');
    }

    /**
     * Resolve the effective multi-clocking setting for an employee.
     */
    private function isMultiClockingEnabled(Employee $employee, ?AttendancePolicy $policy): bool
    {
        // 1. Check Employee Level Override
        $empOverride = AttendanceEmployeeEnforcement::where('employee_id', $employee->id)->first();
        if ($empOverride && $empOverride->multi_clocking != 0) {
            return $empOverride->multi_clocking == 1;
        }

        // 2. Check Role Level Settings
        $user = $employee->user;
        if ($user) {
            $roleIds = $user->roles->pluck('id')->toArray();
            $roleEnforcements = AttendanceRoleEnforcement::whereIn('role_id', $roleIds)
                ->where('multi_clocking', '>', 0)
                ->get();
            
            if ($roleEnforcements->isNotEmpty()) {
                // If any role explicitly allows it, it's allowed
                if ($roleEnforcements->where('multi_clocking', 1)->isNotEmpty()) {
                    return true;
                }
                // If no role allows it but some roles explicitly disallow it
                return false;
            }
        }

        // 3. Fallback to Company Policy
        return $policy ? ($policy->multi_clocking == 1) : false;
    }

    /**
     * Get the effective attendance policy for the current context.
     */
    private function getEffectivePolicy(): ?AttendancePolicy
    {
        // For now, return the default policy for the tenant
        return AttendancePolicy::where('is_active', true)->first();
    }

    /**
     * Store a new Attendance Shift.
     */
    public function storeShift(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:100',
            'start_time'          => 'required|date_format:H:i',
            'end_time'            => 'required|date_format:H:i',
            'grace_minutes'       => 'required|integer|min:0|max:120',
            'half_day_hours'      => 'required|integer|min:1|max:12',
            'min_hours_full_day'  => 'nullable|integer|min:1|max:24',
        ]);

        if ($request->boolean('is_default')) {
            AttendanceShift::where('tenant_id', saas_tenant('id'))->update(['is_default' => false]);
        }

        AttendanceShift::create([
            'tenant_id'          => saas_tenant('id'),
            'name'               => $request->input('name'),
            'start_time'         => $request->input('start_time') . ':00',
            'end_time'           => $request->input('end_time') . ':00',
            'grace_minutes'      => $request->input('grace_minutes', 15),
            'half_day_hours'     => $request->input('half_day_hours', 4),
            'min_hours_full_day' => $request->input('min_hours_full_day'),
            'is_overnight'       => $request->boolean('is_overnight'),
            'is_default'         => $request->boolean('is_default'),
            'is_active'          => true,
        ]);

        return redirect()->route('attendance.settings')->with('success', 'Shift created successfully.');
    }

    /**
     * Delete an Attendance Shift.
     */
    public function deleteShift(AttendanceShift $shift)
    {
        $shift->delete();
        return redirect()->route('attendance.settings')->with('success', 'Shift deleted.');
    }
}
