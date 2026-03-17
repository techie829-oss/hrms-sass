<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Payroll\Models\PayrollRun;
use App\Modules\Payroll\Models\Payslip;

class PayrollPolicy
{
    /**
     * View payroll runs list — tadmin and tmanager only.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    /**
     * View a specific payroll run.
     */
    public function view(User $user, PayrollRun $payrollRun): bool
    {
        if ($payrollRun->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    /**
     * Run / create a new payroll.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    /**
     * View a payslip.
     * - tstaff: can only view their own payslip
     * - tadmin / tmanager: can view any payslip in tenant
     */
    public function viewPayslip(User $user, Payslip $payslip): bool
    {
        if ($payslip->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasRole('tstaff')) {
            return $user->employee?->id === $payslip->employee_id;
        }

        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    /**
     * Delete a payroll run — tadmin only.
     */
    public function delete(User $user, PayrollRun $payrollRun): bool
    {
        if ($payrollRun->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasRole('tadmin');
    }
}
