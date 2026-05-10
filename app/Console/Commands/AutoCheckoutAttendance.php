<?php

namespace App\Console\Commands;

use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\AttendancePolicy;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoCheckoutAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:auto-checkout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically clock out employees based on policy settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $currentTime = $now->format('H:i');

        // Find policies that have auto-checkout enabled for this time
        $policies = AttendancePolicy::where('auto_checkout', true)
            ->whereNotNull('auto_checkout_time')
            ->get();

        foreach ($policies as $policy) {
            $checkoutTimeStr = Carbon::parse($policy->auto_checkout_time)->format('H:i');
            
            // If the current time matches the policy checkout time (or we are within the same minute)
            if ($currentTime === $checkoutTimeStr) {
                $this->processAutoCheckout($policy);
            }
        }
    }

    private function processAutoCheckout(AttendancePolicy $policy)
    {
        $today = Carbon::today();
        
        // Find all active logs for this tenant (linked to this policy)
        $activeLogs = AttendanceLog::where('tenant_id', $policy->tenant_id)
            ->whereDate('date', $today)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->get();

        if ($activeLogs->isEmpty()) {
            return;
        }

        $this->info("Processing auto-checkout for Tenant ID: {$policy->tenant_id} (" . $activeLogs->count() . " logs)");

        foreach ($activeLogs as $log) {
            $checkInTime = Carbon::parse($log->check_in);
            $checkOutTime = Carbon::today()->setTimeFromTimeString($policy->auto_checkout_time);

            // If check-in happened after the auto-checkout time (edge case), skip or use current time
            if ($checkInTime->greaterThan($checkOutTime)) {
                $checkOutTime = Carbon::now();
            }

            // Calculate worked hours
            $diffInMinutes = $checkInTime->diffInMinutes($checkOutTime);
            $workedHours = round($diffInMinutes / 60, 2);

            $log->update([
                'check_out' => $checkOutTime,
                'worked_hours' => $workedHours,
                'remarks' => trim(($log->remarks ?? '') . ' | System Auto-Checkout'),
            ]);

            $this->line(" - Auto-clocked out Employee: {$log->employee_id}");
        }

        Log::info("Attendance: Auto-checkout completed for Tenant {$policy->tenant_id}. " . $activeLogs->count() . " employees processed.");
    }
}
