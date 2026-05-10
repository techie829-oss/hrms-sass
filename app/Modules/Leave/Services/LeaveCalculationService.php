<?php

namespace App\Modules\Leave\Services;

use App\Modules\Leave\Models\Holiday;
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

        // Fetch holidays in the range
        $holidays = Holiday::whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->pluck('date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();

        while ($current->lte($end)) {
            // Check if it's a weekend (Sat/Sun)
            // TODO: In future, this should fetch employee shift/weekly-off settings
            $isWeekend = $current->isWeekend();
            
            // Check if it's a public holiday
            $isHoliday = in_array($current->format('Y-m-d'), $holidays);

            if (!$isWeekend && !$isHoliday) {
                $totalDays++;
            }

            $current->addDay();
        }

        return (float) $totalDays;
    }
}
