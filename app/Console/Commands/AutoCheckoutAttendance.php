<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\AttendancePolicy;
use App\Modules\Attendance\Services\AttendanceSummaryService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoCheckoutAttendance extends Command
{
    protected $signature = 'attendance:auto-checkout';
    protected $description = 'Automatically clock out employees at end of day & recompute summaries';

    public function handle()
    {
        $today = Carbon::today();
        $summaryService = new AttendanceSummaryService();

        // Fetch all tenant schemas — the command runs in central context (no saas_tenant())
        // so we must query using the shared schema prefix directly
        try {
            $policies = AttendancePolicy::withoutGlobalScopes()
                ->where('auto_checkout', true)
                ->whereNotNull('auto_checkout_time')
                ->get();
        } catch (\Throwable $e) {
            // Fallback: try shared schema prefix directly
            $policies = DB::table('shared.attendance_policies')
                ->where('auto_checkout', true)
                ->whereNotNull('auto_checkout_time')
                ->get();
        }

        // Fetch all open attendance logs across all tenants (shared schema)
        try {
            $allActiveLogs = AttendanceLog::withoutGlobalScopes()
                ->whereDate('date', $today)
                ->whereNotNull('check_in')
                ->whereNull('check_out')
                ->get();
        } catch (\Throwable $e) {
            $allActiveLogs = collect(
                DB::select(
                    "SELECT * FROM shared.attendance_logs WHERE date::date = ? AND check_in IS NOT NULL AND check_out IS NULL",
                    [$today->toDateString()]
                )
            );
        }

        foreach ($allActiveLogs as $log) {
            $policy = collect($policies)->where('tenant_id', $log->tenant_id)->first();

            if ($policy) {
                // Auto-checkout: close the session
                $autoTime    = is_object($policy) ? $policy->auto_checkout_time : $policy['auto_checkout_time'];
                $checkOutTime = Carbon::today()->setTimeFromTimeString($autoTime);
                $checkInTime  = Carbon::parse($log->check_in);

                if ($checkInTime->greaterThan($checkOutTime)) {
                    $checkOutTime = Carbon::now()->subMinute();
                }

                $workedHours = round($checkInTime->diffInMinutes($checkOutTime) / 60, 2);

                try {
                    DB::table('shared.attendance_logs')
                        ->where('id', $log->id)
                        ->update([
                            'check_out'    => $checkOutTime,
                            'worked_hours' => $workedHours,
                            'remarks'      => trim(($log->remarks ?? '') . ' | System Auto-Checkout'),
                        ]);
                } catch (\Throwable $e) {
                    Log::warning("AutoCheckout: could not update log #{$log->id}: " . $e->getMessage());
                }

                $this->line(" ✓ Auto-Checkout: Employee {$log->employee_id}");
            }

            // Recompute daily summary
            try {
                $summaryService->recompute($log->employee_id, $log->tenant_id, $today);
            } catch (\Throwable $e) {
                Log::warning("AutoCheckout: summary recompute failed for employee {$log->employee_id}: " . $e->getMessage());
            }
        }

        $count = count($allActiveLogs);
        Log::info("Attendance: Nightly auto-checkout complete. {$count} sessions processed.");
        $this->info("Done. {$count} sessions processed.");
    }
}
