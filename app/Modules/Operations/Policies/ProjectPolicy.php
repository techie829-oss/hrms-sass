<?php

namespace App\Modules\Operations\Policies;

use App\Models\User;
use App\Modules\Operations\Models\Project;
use App\Core\Constants\PermissionConstants;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::VIEW_PROJECTS) || $user->hasPermissionTo(PermissionConstants::MANAGE_PROJECTS);
    }

    public function view(User $user, Project $project): bool
    {
        if ($project->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo(PermissionConstants::VIEW_PROJECTS) || $user->hasPermissionTo(PermissionConstants::MANAGE_PROJECTS);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_PROJECTS);
    }

    public function update(User $user, Project $project): bool
    {
        if ($project->tenant_id !== $user->tenant_id) {
            return false;
        }
        return $user->hasPermissionTo(PermissionConstants::MANAGE_PROJECTS);
    }

    public function delete(User $user, Project $project): bool
    {
        if ($project->tenant_id !== $user->tenant_id) {
            return false;
        }
        return $user->hasPermissionTo(PermissionConstants::MANAGE_PROJECTS);
    }
}
