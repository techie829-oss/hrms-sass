<?php

namespace App\Modules\Attendance\Controllers;

use App\Core\BaseController;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\AttendanceShift;
use App\Modules\Attendance\Models\AttendancePolicy;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use App\Modules\HR\Models\Employee;
use App\Modules\Attendance\Models\AttendanceRoleEnforcement;
use App\Modules\Attendance\Models\AttendanceEmployeeEnforcement;
use App\Modules\Attendance\Services\AttendanceService;
use App\Modules\Attendance\Requests\ClockActionRequest;
use App\Modules\Attendance\DTOs\ClockActionData;
use App\Modules\Attendance\Requests\StoreShiftRequest;
use App\Modules\Attendance\DTOs\ShiftData;

class ClockController extends BaseController
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    public function kiosk()
    {
        $user = auth()->user();
        $employee = $user->employee;
        $today = Carbon::today();

        $todayLog = null;
        $shift = null;

        if ($employee) {
            $todayLog = $this->attendanceService->getLogsByEmployeeAndDate($employee->id, $today->toDateString())->last();
            $shift = $employee->attendanceShift ?: AttendanceShift::where('is_active', true)->where('is_default', true)->first();
        }

        $recentLogs = $employee
            ? AttendanceLog::where('employee_id', $employee->id)->orderBy('date', 'desc')->limit(5)->get()
            : collect();

        $policy = $this->attendanceService->getEffectivePolicy();
        $isMultiEnabled = $employee ? $this->attendanceService->isMultiClockingEnabled($employee, $policy) : false;

        if ($policy && !$policy->is_kiosk_enabled) {
            return redirect()->route('attendance.index')->with('error', 'Kiosk attendance is currently disabled by admin.');
        }

        return view('attendance::kiosk', compact('user', 'employee', 'todayLog', 'shift', 'recentLogs', 'policy', 'isMultiEnabled'));
    }

    public function clockIn(ClockActionRequest $request)
    {
        $dto = ClockActionData::fromRequest($request->validated());
        $result = $this->attendanceService->processClockIn(auth()->user(), $dto->toArray(), $request->ip());

        if ($result['status'] === 'error') {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function clockOut(ClockActionRequest $request)
    {
        $dto = ClockActionData::fromRequest($request->validated());
        $result = $this->attendanceService->processClockOut(auth()->user(), $dto->toArray(), $request->ip());

        if ($result['status'] === 'error') {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function settings()
    {
        $this->authorize('manage', AttendanceLog::class);
        $policy = $this->attendanceService->getEffectivePolicy();
        
        $roles = Role::where('tenant_id', saas_tenant('id'))->get();
        
        $roleEnforcements = AttendanceRoleEnforcement::where('tenant_id', saas_tenant('id'))
            ->pluck('enforce_kiosk', 'role_id')
            ->map(fn($v) => (string)$v)
            ->toArray();
            
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

    public function saveSettings(Request $request)
    {
        $this->authorize('manage', AttendanceLog::class);
        $this->attendanceService->saveSettingsData($request->all());
        return redirect()->route('attendance.settings')->with('success', 'Attendance Clock-In Enforcement settings saved successfully.');
    }

    public function storeShift(StoreShiftRequest $request)
    {
        $dto = ShiftData::fromRequest($request->validated());
        $this->attendanceService->createShift($dto->toArray());

        return redirect()->route('attendance.settings')->with('success', 'Shift created successfully.');
    }

    public function deleteShift(AttendanceShift $shift)
    {
        $this->authorize('manage', AttendanceLog::class);
        $shift->delete();
        return redirect()->route('attendance.settings')->with('success', 'Shift deleted.');
    }
}
