<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Performance\Models\Appraisal;
use App\Modules\Performance\Models\Goal;
use App\Modules\Performance\Models\KPI;

class PerformancePolicy
{
    /**
     * Determine whether the user can view the performance dashboard.
     */
    public function viewDashboard(User $user): bool
    {
        return $user->hasAnyRole(['tadmin', 'tmanager', 'tstaff']);
    }

    /**
     * Determine whether the user can view any performance records.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['tadmin', 'tmanager', 'tstaff']);
    }

    /**
     * Determine whether the user can view a specific appraisal.
     */
    public function viewAppraisal(User $user, Appraisal $appraisal): bool
    {
        if ($appraisal->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasRole('tstaff')) {
            return $user->employee?->id === $appraisal->employee_id;
        }

        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    /**
     * Determine whether the user can view a specific goal.
     */
    public function viewGoal(User $user, Goal $goal): bool
    {
        if ($goal->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasRole('tstaff')) {
            return $user->employee?->id === $goal->employee_id;
        }

        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    /**
     * Determine whether the user can update a specific goal progress.
     */
    public function updateGoal(User $user, Goal $goal): bool
    {
        if ($goal->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasRole('tstaff')) {
            return $user->employee?->id === $goal->employee_id;
        }

        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }
}
