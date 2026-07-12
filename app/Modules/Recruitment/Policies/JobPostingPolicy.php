<?php

namespace App\Modules\Recruitment\Policies;

use App\Models\User;
use App\Modules\Recruitment\Models\JobPosting;
use App\Core\Constants\PermissionConstants;

class JobPostingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::VIEW_RECRUITMENT) ||
               $user->hasPermissionTo(PermissionConstants::MANAGE_RECRUITMENT);
    }

    public function view(User $user, JobPosting $jobPosting): bool
    {
        if ($jobPosting->tenant_id !== $user->tenant_id) return false;
        return $user->hasPermissionTo(PermissionConstants::VIEW_RECRUITMENT) ||
               $user->hasPermissionTo(PermissionConstants::MANAGE_RECRUITMENT);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_RECRUITMENT);
    }

    public function update(User $user, JobPosting $jobPosting): bool
    {
        if ($jobPosting->tenant_id !== $user->tenant_id) return false;
        return $user->hasPermissionTo(PermissionConstants::MANAGE_RECRUITMENT);
    }

    public function delete(User $user, JobPosting $jobPosting): bool
    {
        if ($jobPosting->tenant_id !== $user->tenant_id) return false;
        return $user->hasPermissionTo(PermissionConstants::MANAGE_RECRUITMENT);
    }
}
