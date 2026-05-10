<?php

namespace App\Modules\Leave\Services;

use App\Modules\HR\Models\Employee;
use App\Modules\Leave\Models\LeaveBalance;
use App\Modules\Leave\Models\LeaveType;
use Illuminate\Support\Facades\DB;

class LeaveBalanceService
{
    /**
     * Allocate default leave balances for a new employee.
     */
    public function allocateDefaultBalances(Employee $employee)
    {
        $leaveTypes = LeaveType::where('tenant_id', $employee->tenant_id)
            ->where('is_active', true)
            ->get();

        foreach ($leaveTypes as $type) {
            LeaveBalance::firstOrCreate(
                [
                    'tenant_id' => $employee->tenant_id,
                    'employee_id' => $employee->id,
                    'leave_type_id' => $type->id,
                    'year' => now()->year,
                ],
                [
                    'total_allocated' => $type->max_days_per_year,
                    'balance' => $type->max_days_per_year,
                    'total_used' => 0,
                    'total_pending' => 0,
                    'carried_forward' => 0,
                ]
            );
        }
    }

    /**
     * Get the current available balance for an employee and leave type.
     */
    public function getAvailableBalance(Employee $employee, LeaveType $type, int $year = null)
    {
        $year = $year ?? now()->year;

        $balance = LeaveBalance::where([
            'employee_id' => $employee->id,
            'leave_type_id' => $type->id,
            'year' => $year,
        ])->first();

        return $balance ? $balance->balance : 0;
    }
}
