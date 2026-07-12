<?php

namespace App\Modules\Attendance\Services;

use App\Core\BaseService;
use App\Modules\Attendance\Interfaces\AttendanceRepositoryInterface;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\HR\Models\Employee;
use App\Modules\Attendance\Models\AttendancePolicy;
use App\Modules\Attendance\Models\AttendanceShift;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Modules\Attendance\Models\AttendanceRoleEnforcement;
use App\Modules\Attendance\Models\AttendanceEmployeeEnforcement;

/**
 * @property AttendanceRepositoryInterface $repository
 */
class AttendanceService extends BaseService
{
    public function __construct(AttendanceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function checkIn(int $employeeId, array $data)
    {
        return $this->repository->checkIn($employeeId, $data);
    }

    public function checkOut(int $employeeId, array $data)
    {
        return $this->repository->checkOut($employeeId, $data);
    }

    public function getLogById(int $id)
    {
        return $this->repository->getLogById($id);
    }

    public function getLogsByEmployeeAndDate(int $employeeId, string $date)
    {
        return $this->repository->getLogsByEmployeeAndDate($employeeId, $date);
    }

    public function getSummaryQuery(array $filters)
    {
        return $this->repository->getSummaryQuery($filters);
    }

    public function updateShiftAssignments(array $assignments)
    {
        foreach ($assignments as $assignment) {
            $this->repository->updateEmployeeShift($assignment['employee_id'], $assignment['shift_id']);
        }
    }

    public function getActiveShifts()
    {
        return $this->repository->getActiveShifts();
    }

    public function getEmployeesWithShifts()
    {
        return $this->repository->getEmployeesWithShifts();
    }

    public function isMultiClockingEnabled(Employee $employee, ?AttendancePolicy $policy): bool
    {
        $empOverride = AttendanceEmployeeEnforcement::where('employee_id', $employee->id)->first();
        if ($empOverride && $empOverride->multi_clocking != 0) {
            return $empOverride->multi_clocking == 1;
        }

        $user = $employee->user;
        if ($user) {
            $roleIds = $user->roles->pluck('id')->toArray();
            $roleEnforcements = AttendanceRoleEnforcement::whereIn('role_id', $roleIds)
                ->where('multi_clocking', '>', 0)
                ->get();
            
            if ($roleEnforcements->isNotEmpty()) {
                if ($roleEnforcements->where('multi_clocking', 1)->isNotEmpty()) {
                    return true;
                }
                return false;
            }
        }

        return $policy ? ($policy->multi_clocking == 1) : false;
    }

    public function getEffectivePolicy(): ?AttendancePolicy
    {
        return AttendancePolicy::where('is_active', true)->first();
    }

    public function savePhoto(?string $base64, int $employeeId, string $type): ?string
    {
        if (!$base64 || !str_contains($base64, 'base64,')) {
            return null;
        }

        $imageData = base64_decode(explode(',', $base64)[1]);
        $filename = 'attendance-photos/' . $employeeId . '_' . $type . '_' . now()->format('Ymd_His') . '.jpg';
        Storage::disk('public')->put($filename, $imageData);

        return $filename;
    }

    public function processClockIn($user, array $data, string $ip)
    {
        $employee = $user->employee;
        $policy = $this->getEffectivePolicy();

        if (!$employee) {
            return ['status' => 'error', 'message' => 'Employee record not found for your user account.'];
        }

        $now = Carbon::now();
        $today = Carbon::today();

        $existing = AttendanceLog::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->latest()
            ->first();

        $isMultiEnabled = $this->isMultiClockingEnabled($employee, $policy);

        if ($existing) {
            if (!$isMultiEnabled) {
                return ['status' => 'error', 'message' => 'You have already clocked in today.'];
            }
            if ($existing->check_in && !$existing->check_out) {
                return ['status' => 'error', 'message' => 'You are already clocked in. Please clock out first.'];
            }
        }

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
                $lateMinutes = (int) abs($now->diffInMinutes($startTime));
            }
        }

        $photoPath = $this->savePhoto($data['photo'] ?? null, $employee->id, 'checkin');

        AttendanceLog::create([
            'tenant_id' => saas_tenant('id'),
            'employee_id' => $employee->id,
            'attendance_shift_id' => $shift?->id,
            'date' => $today,
            'check_in' => $now,
            'status' => $isLate ? 'late' : 'present',
            'is_late' => $isLate,
            'late_minutes' => $lateMinutes,
            'check_in_ip' => $ip,
            'check_in_lat' => $data['latitude'] ?? null,
            'check_in_lng' => $data['longitude'] ?? null,
            'check_in_photo' => $photoPath,
            'remarks' => $data['device_info'] ?? null,
        ]);

        $msg = 'Clocked in at ' . $now->format('h:i A');
        if ($isLate) {
            $msg .= ' — Late by ' . $lateMinutes . ' minutes';
        }

        (new AttendanceSummaryService())->recompute($employee->id, saas_tenant('id'), $today);

        return ['status' => 'success', 'message' => $msg];
    }

    public function processClockOut($user, array $data, string $ip)
    {
        $employee = $user->employee;
        $policy = $this->getEffectivePolicy();

        if (!$employee) {
            return ['status' => 'error', 'message' => 'Employee record not found.'];
        }

        $today = Carbon::today();

        $log = AttendanceLog::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->latest()
            ->first();

        if (!$log) {
            return ['status' => 'error', 'message' => 'No active clock-in found for today.'];
        }

        $checkOutTime = Carbon::now();
        $checkInTime = Carbon::parse($log->check_in);
        
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
                $overtimeMinutes = (int) abs($checkOutTime->diffInMinutes($endTime));
            }
        }

        $photoPath = $this->savePhoto($data['photo'] ?? null, $employee->id, 'checkout');

        $existingRemarks = $log->remarks ?? '';
        $deviceInfo = $data['device_info'] ?? null;
        $updatedRemarks = $existingRemarks;
        if ($deviceInfo) {
            $updatedRemarks = trim($existingRemarks . ' | OUT: ' . $deviceInfo);
        }

        $log->update([
            'check_out' => $checkOutTime,
            'worked_hours' => $workedHours,
            'overtime_minutes' => $overtimeMinutes,
            'check_out_ip' => $ip,
            'check_out_lat' => $data['latitude'] ?? null,
            'check_out_lng' => $data['longitude'] ?? null,
            'check_out_photo' => $photoPath,
            'remarks' => $updatedRemarks,
        ]);

        (new AttendanceSummaryService())->recompute($employee->id, saas_tenant('id'), $today);

        $msg = 'Clocked out at ' . $checkOutTime->format('h:i A') . '. Worked ' . $workedHours . ' hours.';
        if ($overtimeMinutes > 0) {
            $msg .= ' (Overtime: ' . $overtimeMinutes . ' mins)';
        }

        return ['status' => 'success', 'message' => $msg];
    }
    
    public function saveSettingsData(array $data)
    {
        $policy = $this->getEffectivePolicy();
        if ($policy) {
            $policy->update([
                'enforce_clockin'    => isset($data['enforce_clockin']),
                'multi_clocking'     => (int)($data['multi_clocking_policy'] ?? 0),
                'auto_checkout'      => isset($data['auto_checkout']),
                'auto_checkout_time' => $data['auto_checkout_time'] ?? null,
                'default_start_time' => $data['default_start_time'] ?? null,
                'default_end_time'   => $data['default_end_time'] ?? null,
                'min_hours_full_day'          => (int)($data['min_hours_full_day'] ?? 8),
                'min_hours_half_day'          => (int)($data['min_hours_half_day'] ?? 4),
                'late_mark_after_minutes'     => (int)($data['late_mark_after_minutes'] ?? 15),
                'early_leave_before_minutes'  => (int)($data['early_leave_before_minutes'] ?? 30),
                'max_late_allowed_per_month'  => (int)($data['max_late_allowed_per_month'] ?? 3),
                'auto_deduct_leave'           => isset($data['auto_deduct_leave']),
                'is_kiosk_enabled'       => isset($data['is_kiosk_enabled']),
                'kiosk_require_photo'    => isset($data['kiosk_require_photo']),
                'kiosk_require_location' => isset($data['kiosk_require_location']),
            ]);
        }

        AttendanceRoleEnforcement::where('tenant_id', saas_tenant('id'))->delete();
        $rolesData = $data['roles'] ?? [];
        $multiRolesData = $data['multi_roles'] ?? [];
        
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

        AttendanceEmployeeEnforcement::where('tenant_id', saas_tenant('id'))->delete();
        $employeesData      = $data['employees'] ?? [];
        $multiEmployeesData = $data['multi_employees'] ?? [];
        $flexibleEmployeesData = $data['flexible_employees'] ?? [];

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
                AttendanceEmployeeEnforcement::where('tenant_id', saas_tenant('id'))
                    ->where('employee_id', $employeeId)
                    ->delete();
            }
        }
    }

    public function createShift(array $data)
    {
        if (!empty($data['is_default'])) {
            AttendanceShift::where('tenant_id', saas_tenant('id'))->update(['is_default' => false]);
        }

        AttendanceShift::create([
            'tenant_id'          => saas_tenant('id'),
            'name'               => $data['name'],
            'start_time'         => $data['start_time'] . ':00',
            'end_time'           => $data['end_time'] . ':00',
            'grace_minutes'      => $data['grace_minutes'] ?? 0,
            'half_day_hours'     => $data['half_day_hours'] ?? 4,
            'min_hours_full_day' => $data['min_hours_full_day'] ?? 8,
            'is_overnight'       => $data['is_overnight'] ?? false,
            'weekly_offs'        => $data['weekly_offs'] ?? [],
            'is_default'         => !empty($data['is_default']),
            'is_active'          => true,
        ]);
    }
}
