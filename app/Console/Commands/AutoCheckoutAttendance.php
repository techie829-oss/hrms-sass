<?php

namespace App\Console\Commands;

use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\AttendancePolicy;
use App\Modules\Attendance\Services\AttendanceSummaryService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoCheckoutAttendance extends Command
{
    protected $signature = 'attendance:auto-checkout';
    protected $description = 'Automatically clock out employees at end of day & recompute summaries';

    public function handle()
    {
        $now = Carbon::now();
        $today = Carbon::today();
        $summaryService = new AttendanceSummaryService();

        // Get all tenants with auto-checkout enabled
        $policies = AttendancePolicy::where('auto_checkout', true)
            ->whereNotNull('auto_checkout_time')
            ->get();

        // Also tag all sessions with checkout_missing for ALL tenants (nightly run)
        $allActiveLogs = AttendanceLog::whereDate('date', $today)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->get();

        foreach ($allActiveLogs as $log) {
            $policy = $policies->where('tenant_id', $log->tenant_id)->first();

            if ($policy) {
                // Auto-checkout: close the session
                $checkOutTime = Carbon::today()->setTimeFromTimeString($policy->auto_checkout_time);
                $checkInTime  = Carbon::parse($log->check_in);

                if ($checkInTime->greaterThan($checkOutTime)) {
                    $checkOutTime = Carbon::now()->subMinute();
                }

                $workedHours = round($checkInTime->diffInMinutes($checkOutTime) / 60, 2);

                $log->update([
                    'check_out'    => $checkOutTime,
                    'worked_hours' => $workedHours,
                    'remarks'      => trim(($log->remarks ?? '') . ' | System Auto-Checkout'),
                ]);

                $this->line(" ✓ Auto-Checkout: Employee {$log->employee_id}");
            }

            // Recompute daily summary (will set checkout_missing tag if needed)
            $summaryService->recompute($log->employee_id, $log->tenant_id, $today);
        }

        // Also recompute for employees that were absent (no logs today) — mark absent
        // This is optional; the index view handles it already

        $count = $allActiveLogs->count();
        Log::info("Attendance: Nightly auto-checkout complete. {$count} sessions processed.");
        $this->info("Done. {$count} sessions processed.");
    }
}
