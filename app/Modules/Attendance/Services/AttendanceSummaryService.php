<?php

namespace App\Modules\Attendance\Services;

use App\Modules\Attendance\Models\AttendanceDailySummary;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\AttendancePolicy;
use App\Modules\Attendance\Models\AttendanceShift;
use App\Modules\Attendance\Models\AttendanceEmployeeEnforcement;
use App\Modules\HR\Models\Employee;
use Carbon\Carbon;

class AttendanceSummaryService
{
    /**
     * Recompute the daily summary for a given employee on a given date.
     * Call this after every clock-in or clock-out.
     */
    public function recompute(int $employeeId, string $tenantId, Carbon $date): AttendanceDailySummary
    {
        $dateStr = $date->toDateString();

        // Fetch all punch logs for this employee on this date
        $logs = AttendanceLog::where('tenant_id', $tenantId)
            ->where('employee_id', $employeeId)
            ->whereDate('date', $dateStr)
            ->orderBy('check_in')
            ->get();

        // Fetch policy and shift
        $policy = AttendancePolicy::where('is_active', true)->first();
        $employee = Employee::find($employeeId);
        $shift = $employee?->attendanceShift
            ?? AttendanceShift::where('is_active', true)->where('is_default', true)->first();

        // --- Core Calculations ---
        $firstIn   = $logs->whereNotNull('check_in')->first()?->check_in;
        $lastOut   = $logs->whereNotNull('check_out')->sortByDesc('check_out')->first()?->check_out;

        // Total worked hours = sum of all complete punch pairs
        $totalWorkedMinutes = 0;
        foreach ($logs as $log) {
            if ($log->check_in && $log->check_out) {
                $totalWorkedMinutes += abs(Carbon::parse($log->check_in)
                    ->diffInMinutes(Carbon::parse($log->check_out)));
            }
        }
        $totalWorkedHours = abs(round($totalWorkedMinutes / 60, 2));

        $totalSessions = $logs->whereNotNull('check_in')->count();

        // --- Determine Shift Mode ---
        // Flexible is per-EMPLOYEE, not per-shift.
        // Check employee enforcement override first.
        $empEnforcement = AttendanceEmployeeEnforcement::where('employee_id', $employeeId)
            ->where('tenant_id', $tenantId)
            ->first();
        $isFlexible = $empEnforcement?->is_flexible ?? false;

        // Shift-level min hours can override policy (for special part-time/custom shifts)
        $fullDayHours = $shift?->min_hours_full_day ?? ($policy?->min_hours_full_day ?? 8);
        $halfDayHours  = $policy?->min_hours_half_day ?? 4;

        // --- Late & Early Leave (Only for Fixed Shifts) ---
        $lateMinutes       = 0;
        $isLate            = false;
        $overtimeMinutes   = 0;
        $earlyLeaveMinutes = 0;

        if (!$isFlexible && $firstIn) {
            // Grace period from shift or policy
            $graceMinutes  = $shift?->grace_minutes ?? ($policy?->late_mark_after_minutes ?? 15);
            $earlyLeaveMin = $policy?->early_leave_before_minutes ?? 30;

            // Late Mark Calculation
            $targetStartTime = $shift?->start_time ?? $policy?->default_start_time;
            if ($targetStartTime) {
                $expected = Carbon::parse($targetStartTime)->setDateFrom($date);
                $actualIn = Carbon::parse($firstIn);
                if ($actualIn->greaterThan($expected->copy()->addMinutes($graceMinutes))) {
                    $isLate = true;
                    $lateMinutes = max(0, (int) round(abs($actualIn->diffInMinutes($expected))));
                }
            }

            // Overtime & Early Leave Calculation
            if ($lastOut) {
                $targetEndTime = $shift?->end_time ?? $policy?->default_end_time;
                if ($targetEndTime) {
                    $expected  = Carbon::parse($targetEndTime)->setDateFrom($date);
                    $actualOut = Carbon::parse($lastOut);
                    if ($actualOut->greaterThan($expected)) {
                        $overtimeMinutes = max(0, (int) round(abs($actualOut->diffInMinutes($expected))));
                    } elseif ($actualOut->lessThan($expected->copy()->subMinutes($earlyLeaveMin))) {
                        $earlyLeaveMinutes = max(0, (int) round(abs($actualOut->diffInMinutes($expected))));
                    }
                }
            }
        } elseif ($isFlexible) {
            // Flexible: Only check overtime (worked more than full day hours)
            if ($totalWorkedHours > $fullDayHours) {
                $overtimeMinutes = max(0, (int) round(($totalWorkedHours - $fullDayHours) * 60));
            }
        }

        // --- Checkout Missing Check ---
        $hasOpenSession = $logs->whereNotNull('check_in')->whereNull('check_out')->isNotEmpty();

        // --- Day Type Classification ---
        $dayType = 'absent';
        if ($logs->isEmpty()) {
            $dayType = 'absent';
        } elseif ($totalWorkedHours >= $fullDayHours) {
            $dayType = 'full_day';
        } elseif ($totalWorkedHours >= $halfDayHours) {
            $dayType = 'half_day';
        } elseif ($totalWorkedHours > 0) {
            $dayType = 'quarter_day';
        }

        // --- Status ---
        $status = match($dayType) {
            'full_day'     => $isLate ? 'late' : 'present',
            'half_day'     => 'half_day',
            'quarter_day'  => 'late',
            default        => 'absent',
        };

        // --- Build Smart Tags Array ---
        $tags = [];
        if ($isLate)           $tags[] = 'late_arrived';
        if ($hasOpenSession)   $tags[] = 'checkout_missing';
        if ($overtimeMinutes > 0) $tags[] = 'overtime';
        if ($earlyLeaveMinutes > 0) $tags[] = 'early_leave';
        if ($totalSessions > 1)  $tags[] = 'multi_session';

        // --- Upsert Summary ---
        $summary = AttendanceDailySummary::updateOrCreate(
            [
                'tenant_id'   => $tenantId,
                'employee_id' => $employeeId,
                'date'        => $dateStr,
            ],
            [
                'first_check_in'       => $firstIn,
                'last_check_out'       => $lastOut,
                'total_worked_hours'   => $totalWorkedHours,
                'total_sessions'       => $totalSessions,
                'day_type'             => $dayType,
                'tags'                 => $tags,
                'late_minutes'         => $lateMinutes,
                'overtime_minutes'     => $overtimeMinutes,
                'early_leave_minutes'  => $earlyLeaveMinutes,
                'status'               => $status,
            ]
        );

        return $summary;
    }
}
