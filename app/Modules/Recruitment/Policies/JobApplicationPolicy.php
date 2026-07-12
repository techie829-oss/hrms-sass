<?php

namespace App\Modules\Recruitment\Policies;

use App\Models\User;
use App\Modules\Recruitment\Models\JobApplication;
use App\Core\Constants\PermissionConstants;

class JobApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::VIEW_RECRUITMENT) ||
               $user->hasPermissionTo(PermissionConstants::MANAGE_RECRUITMENT);
    }

    public function view(User $user, JobApplication $application): bool
    {
        if ($application->tenant_id !== $user->tenant_id) return false;
        return $user->hasPermissionTo(PermissionConstants::VIEW_RECRUITMENT) ||
               $user->hasPermissionTo(PermissionConstants::MANAGE_RECRUITMENT);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_RECRUITMENT);
    }

    public function update(User $user, JobApplication $application): bool
    {
        if ($application->tenant_id !== $user->tenant_id) return false;
        return $user->hasPermissionTo(PermissionConstants::MANAGE_RECRUITMENT);
    }

    public function delete(User $user, JobApplication $application): bool
    {
        if ($application->tenant_id !== $user->tenant_id) return false;
        return $user->hasPermissionTo(PermissionConstants::MANAGE_RECRUITMENT);
    }
}
