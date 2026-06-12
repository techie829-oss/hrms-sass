<?php

namespace App\Modules\HR\Policies;

use App\Models\User;
use App\Modules\HR\Models\Employee;
use App\Core\Constants\PermissionConstants;

class EmployeePolicy
{
    /**
     * Ensure Spatie uses the correct tenant scope before any permission check.
     * This is needed because Policy checks happen outside the middleware cycle
     * and Spatie's team ID may not be set correctly via the cached permission store.
     */
    private function scopeTeam(User $user): void
    {
        if ($user->tenant_id && function_exists('setPermissionsTeamId')) {
            setPermissionsTeamId($user->tenant_id);
        }
    }

    /**
     * List all employees.
     */
    public function viewAny(User $user): bool
    {
        $this->scopeTeam($user);
        return $user->hasPermissionTo(PermissionConstants::VIEW_EMPLOYEES);
    }

    /**
     * View a specific employee record.
     * Allows if user has 'view-employees' permission OR is viewing their own record.
     */
    public function view(User $user, Employee $employee): bool
    {
        if ($employee->tenant_id !== $user->tenant_id) {
            return false; // Cross-tenant isolation
        }

        $this->scopeTeam($user);

        if ($user->hasPermissionTo(PermissionConstants::VIEW_EMPLOYEES)) {
            return true;
        }

        return $user->employee?->id === $employee->id;
    }

    /**
     * Create a new employee.
     */
    public function create(User $user): bool
    {
        $this->scopeTeam($user);
        return $user->hasPermissionTo(PermissionConstants::CREATE_EMPLOYEES);
    }

    /**
     * Update an employee record.
     * Allows if user has 'edit employees' permission OR is updating their own profile.
     */
    public function update(User $user, Employee $employee): bool
    {
        if ($employee->tenant_id !== $user->tenant_id) {
            return false;
        }

        $this->scopeTeam($user);

        if ($user->hasPermissionTo(PermissionConstants::EDIT_EMPLOYEES)) {
            return true;
        }

        // Allow employee to edit their own profile
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

        $this->scopeTeam($user);

        return $user->hasPermissionTo(PermissionConstants::DELETE_EMPLOYEES);
    }
}
