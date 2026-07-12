<?php

namespace App\Modules\Operations\Policies;

use App\Models\User;
use App\Modules\Operations\Models\Timesheet;
use App\Core\Constants\PermissionConstants;

class TimesheetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            PermissionConstants::VIEW_TIMESHEET,
            PermissionConstants::MANAGE_TIMESHEET,
            PermissionConstants::VIEW_OWN_TIMESHEET
        ]);
    }

    public function view(User $user, Timesheet $timesheet): bool
    {
        if ($timesheet->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasPermissionTo(PermissionConstants::MANAGE_TIMESHEET) || $user->hasPermissionTo(PermissionConstants::VIEW_TIMESHEET)) {
            return true;
        }
        
        return $user->hasPermissionTo(PermissionConstants::VIEW_OWN_TIMESHEET) && $user->employee?->id === $timesheet->employee_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission([
            PermissionConstants::MANAGE_TIMESHEET,
            PermissionConstants::VIEW_OWN_TIMESHEET
        ]);
    }

    public function update(User $user, Timesheet $timesheet): bool
    {
        if ($timesheet->tenant_id !== $user->tenant_id) {
            return false;
        }
        
        if ($user->hasPermissionTo(PermissionConstants::MANAGE_TIMESHEET)) {
            return true;
        }
        
        return $user->hasPermissionTo(PermissionConstants::VIEW_OWN_TIMESHEET) && $user->employee?->id === $timesheet->employee_id;
    }

    public function delete(User $user, Timesheet $timesheet): bool
    {
        if ($timesheet->tenant_id !== $user->tenant_id) {
            return false;
        }
        
        if ($user->hasPermissionTo(PermissionConstants::MANAGE_TIMESHEET)) {
            return true;
        }
        
        return $user->hasPermissionTo(PermissionConstants::VIEW_OWN_TIMESHEET) && $user->employee?->id === $timesheet->employee_id;
    }
}
