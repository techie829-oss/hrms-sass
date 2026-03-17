<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Modules\HR\Models\Employee;
use App\Modules\Leave\Models\LeaveType;
use App\Modules\Leave\Models\LeaveBalance;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AccrueLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:accrue {--tenant= : Run for a specific tenant ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Accrue monthly leaves for employees based on leave types';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantId = $this->option('tenant');

        if ($tenantId) {
            $tenant = Tenant::find($tenantId);
            if (!$tenant) {
                $this->error("Tenant {$tenantId} not found.");
                return;
            }
            $this->accrueForTenant($tenant);
        } else {
            Tenant::all()->each(function ($tenant) {
                $this->accrueForTenant($tenant);
            });
        }

        $this->info('Leave accrual completed successfully.');
    }

    protected function accrueForTenant($tenant)
    {
        $this->info("Accruing leaves for tenant: {$tenant->id}");
        
        tenancy()->initialize($tenant);

        $currentYear = Carbon::now()->year;
        $leaveTypes = LeaveType::where('is_active', true)->get();
        $employees = Employee::where('status', 'active')->get();

        foreach ($employees as $employee) {
            foreach ($leaveTypes as $type) {
                // Monthly accrual = max_days_per_year / 12
                $accrualAmount = round($type->max_days_per_year / 12, 1);

                if ($accrualAmount <= 0) continue;

                $balance = LeaveBalance::firstOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'employee_id' => $employee->id,
                        'leave_type_id' => $type->id,
                        'year' => $currentYear,
                    ],
                    [
                        'total_allocated' => 0,
                        'total_used' => 0,
                        'total_pending' => 0,
                        'carried_forward' => 0,
                        'balance' => 0,
                    ]
                );

                $newAllocated = $balance->total_allocated + $accrualAmount;
                // If allocated exceeds max_days_per_year, cap it (optional, but good for some policies)
                // For now, let's just add it.
                
                $balance->update([
                    'total_allocated' => $newAllocated,
                    'balance' => $newAllocated - $balance->total_used,
                ]);
            }
        }

        tenancy()->end();
    }
}
