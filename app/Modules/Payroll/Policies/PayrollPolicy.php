<?php

namespace App\Modules\Payroll\Policies;

use App\Models\User;
use App\Modules\Payroll\Models\PayrollRun;
use App\Modules\Payroll\Models\Payslip;

use App\Core\Constants\PermissionConstants;

class PayrollPolicy
{
    /**
     * View payroll runs list.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::VIEW_PAYROLL) || 
               $user->hasPermissionTo(PermissionConstants::VIEW_OWN_PAYROLL) ||
               $user->hasPermissionTo(PermissionConstants::MANAGE_PAYROLL);
    }

    /**
     * View a specific payroll run.
     */
    public function view(User $user, PayrollRun $payrollRun): bool
    {
        if ($payrollRun->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo(PermissionConstants::VIEW_PAYROLL) || 
               $user->hasPermissionTo(PermissionConstants::VIEW_OWN_PAYROLL) ||
               $user->hasPermissionTo(PermissionConstants::MANAGE_PAYROLL);
    }

    /**
     * Run / create a new payroll.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_PAYROLL);
    }

    /**
     * View a payslip.
     */
    public function viewPayslip(User $user, Payslip $payslip): bool
    {
        if ($payslip->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasPermissionTo(PermissionConstants::MANAGE_PAYROLL) || $user->hasPermissionTo(PermissionConstants::VIEW_PAYROLL)) {
            return true;
        }

        if ($user->hasPermissionTo(PermissionConstants::VIEW_OWN_PAYROLL)) {
            return $user->employee?->id === $payslip->employee_id;
        }

        return false;
    }

    /**
     * Delete a payroll run.
     */
    public function delete(User $user, PayrollRun $payrollRun): bool
    {
        if ($payrollRun->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo(PermissionConstants::MANAGE_PAYROLL);
    }

    /**
     * Manage payroll settings / general components.
     */
    public function manage(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_PAYROLL);
    }
}
