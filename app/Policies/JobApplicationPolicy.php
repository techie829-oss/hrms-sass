<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Recruitment\Models\JobApplication;

class JobApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    public function view(User $user, JobApplication $application): bool
    {
        return $application->tenant_id === $user->tenant_id && $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    public function update(User $user, JobApplication $application): bool
    {
        return $application->tenant_id === $user->tenant_id && $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    public function delete(User $user, JobApplication $application): bool
    {
        return $application->tenant_id === $user->tenant_id && $user->hasRole('tadmin');
    }
}
