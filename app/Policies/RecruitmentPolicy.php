<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Recruitment\Models\JobPosting;

class JobPostingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    public function view(User $user, JobPosting $jobPosting): bool
    {
        return $jobPosting->tenant_id === $user->tenant_id && $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    public function update(User $user, JobPosting $jobPosting): bool
    {
        return $jobPosting->tenant_id === $user->tenant_id && $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    public function delete(User $user, JobPosting $jobPosting): bool
    {
        return $jobPosting->tenant_id === $user->tenant_id && $user->hasRole('tadmin');
    }
}
