<?php

namespace App\Modules\Operations\Policies;

use App\Models\User;
use App\Modules\Operations\Models\Task;
use App\Core\Constants\PermissionConstants;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::VIEW_TASKS) || $user->hasPermissionTo(PermissionConstants::MANAGE_TASKS);
    }

    public function view(User $user, Task $task): bool
    {
        if ($task->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo(PermissionConstants::VIEW_TASKS) || $user->hasPermissionTo(PermissionConstants::MANAGE_TASKS);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_TASKS);
    }

    public function update(User $user, Task $task): bool
    {
        if ($task->tenant_id !== $user->tenant_id) {
            return false;
        }
        return $user->hasPermissionTo(PermissionConstants::MANAGE_TASKS);
    }

    public function delete(User $user, Task $task): bool
    {
        if ($task->tenant_id !== $user->tenant_id) {
            return false;
        }
        return $user->hasPermissionTo(PermissionConstants::MANAGE_TASKS);
    }
}
