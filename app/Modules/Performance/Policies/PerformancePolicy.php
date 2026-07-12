<?php

namespace App\Modules\Performance\Policies;

use App\Models\User;
use App\Modules\Performance\Models\Appraisal;
use App\Modules\Performance\Models\Goal;
use App\Modules\Performance\Models\KPI;
use App\Core\Constants\PermissionConstants;

class PerformancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::VIEW_PERFORMANCE) || 
               $user->hasPermissionTo(PermissionConstants::VIEW_OWN_PERFORMANCE) ||
               $user->hasPermissionTo(PermissionConstants::MANAGE_PERFORMANCE);
    }

    public function view(User $user, $model): bool
    {
        if ($model->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasPermissionTo(PermissionConstants::VIEW_PERFORMANCE) || 
            $user->hasPermissionTo(PermissionConstants::MANAGE_PERFORMANCE)) {
            return true;
        }

        if ($user->hasPermissionTo(PermissionConstants::VIEW_OWN_PERFORMANCE)) {
            if (isset($model->employee_id)) {
                return $user->employee?->id === $model->employee_id;
            }
            return true; // e.g. KPI
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_PERFORMANCE);
    }

    public function update(User $user, $model): bool
    {
        if ($model->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasPermissionTo(PermissionConstants::MANAGE_PERFORMANCE)) {
            return true;
        }

        if ($model instanceof Goal && $user->hasPermissionTo(PermissionConstants::VIEW_OWN_PERFORMANCE)) {
            return $user->employee?->id === $model->employee_id;
        }

        return false;
    }

    public function delete(User $user, $model): bool
    {
        if ($model->tenant_id !== $user->tenant_id) {
            return false;
        }
        
        return $user->hasPermissionTo(PermissionConstants::MANAGE_PERFORMANCE);
    }
}
