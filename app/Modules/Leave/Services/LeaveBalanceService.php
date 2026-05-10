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
     * Adjust (increment/decrement) leave balance for an employee.
     * Useful for Comp-Off additions or manual corrections.
     */
    public function adjustBalance(Employee $employee, string $typeCode, float $amount, int $year = null)
    {
        $year = $year ?? now()->year;
        $leaveType = LeaveType::where('tenant_id', $employee->tenant_id)
            ->where('code', $typeCode)
            ->first();

        if (!$leaveType) return false;

        $balance = LeaveBalance::firstOrCreate(
            [
                'tenant_id' => $employee->tenant_id,
                'employee_id' => $employee->id,
                'leave_type_id' => $leaveType->id,
                'year' => $year,
            ],
            [
                'total_allocated' => 0,
                'balance' => 0,
                'total_used' => 0,
                'total_pending' => 0,
                'carried_forward' => 0,
            ]
        );

        $balance->increment('total_allocated', $amount);
        $balance->increment('balance', $amount);

        return $balance;
    }
}
