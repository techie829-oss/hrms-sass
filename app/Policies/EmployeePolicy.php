<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\HR\Models\Employee;

class EmployeePolicy
{
    /**
     * List all employees — tadmin and tmanager only.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['tadmin', 'tmanager', 'tstaff']);
    }

    /**
     * View a specific employee record.
     * - tstaff: can only view their own record
     * - tadmin / tmanager: can view any employee in same tenant
     */
    public function view(User $user, Employee $employee): bool
    {
        if ($employee->tenant_id !== $user->tenant_id) {
            return false; // Cross-tenant isolation
        }

        if ($user->hasRole('tstaff')) {
            return $user->employee?->id === $employee->id;
        }

        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    /**
     * Create a new employee — tadmin and tmanager only.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    /**
     * Update an employee record.
     * - Only within same tenant.
     * - tstaff can update their own profile (limited fields — enforce in controller).
     */
    public function update(User $user, Employee $employee): bool
    {
        if ($employee->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasRole('tstaff')) {
            return $user->employee?->id === $employee->id;
        }

        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    /**
     * Delete an employee — tadmin only.
     */
    public function delete(User $user, Employee $employee): bool
    {
        if ($employee->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasRole('tadmin');
    }
}
