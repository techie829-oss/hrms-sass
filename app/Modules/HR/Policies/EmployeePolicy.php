<?php

namespace App\Modules\HR\Policies;

use App\Models\User;
use App\Modules\HR\Models\Employee;

class EmployeePolicy
{
    /**
     * List all employees.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view employees');
    }

    /**
     * View a specific employee record.
     * - Allows if user has 'view employees' permission OR is viewing their own record.
     */
    public function view(User $user, Employee $employee): bool
    {
        if ($employee->tenant_id !== $user->tenant_id) {
            return false; // Cross-tenant isolation
        }

        if ($user->hasPermissionTo('view employees')) {
            return true;
        }

        return $user->employee?->id === $employee->id;
    }

    /**
     * Create a new employee.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create employees');
    }

    /**
     * Update an employee record.
     * - Allows if user has 'edit employees' permission OR is updating their own profile.
     */
    public function update(User $user, Employee $employee): bool
    {
        if ($employee->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasPermissionTo('edit employees')) {
            return true;
        }

        return $user->employee?->id === $employee->id;
    }

    /**
     * Delete an employee.
     */
    public function delete(User $user, Employee $employee): bool
    {
        if ($employee->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo('delete employees');
    }
}
