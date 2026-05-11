<?php

namespace App\Modules\Leave\Services;

use App\Modules\Leave\Models\Holiday;
use App\Modules\HR\Models\Employee;
use App\Modules\Attendance\Models\AttendanceShift;
use Carbon\Carbon;

class LeaveCalculationService
{
    /**
     * Calculate total leave days excluding weekends and public holidays.
     */
    public function calculateNetDays($startDate, $endDate, $employeeId = null): float
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $totalDays = 0;
        $current = $start->copy();

        // 1. Default fallback (Standard weekends)
        $weeklyOffs = ['Saturday', 'Sunday'];

        // 2. Try to get specific off-days for this employee
        if ($employeeId) {
            $employee = Employee::with('attendanceShift')->find($employeeId);
            
            $shift = null;
            if ($employee && $employee->attendanceShift) {
                // Use assigned shift
                $shift = $employee->attendanceShift;
            } else {
                // Fallback to Tenant's Default Shift
                $shift = AttendanceShift::where('tenant_id', saas_tenant('id'))
                    ->where('is_default', true)
                    ->first();
            }

            if ($shift && is_array($shift->weekly_offs)) {
                $weeklyOffs = $shift->weekly_offs;
            }
        }

        // Fetch holidays in the range
        $holidays = Holiday::whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->pluck('date')
            ->map(fn($date) => (is_string($date) ? Carbon::parse($date) : $date)->format('Y-m-d'))
            ->toArray();

        while ($current->lte($end)) {
            // Check if it's a weekly off day
            $isWeeklyOff = in_array($current->format('l'), $weeklyOffs);
            
            // Check if it's a public holiday
            $isHoliday = in_array($current->format('Y-m-d'), $holidays);

            if (!$isWeeklyOff && !$isHoliday) {
                $totalDays++;
            }

            $current->addDay();
        }

        return (float) $totalDays;
    }
}
