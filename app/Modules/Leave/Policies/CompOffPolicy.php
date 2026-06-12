<?php

namespace App\Modules\Leave\Policies;

use App\Models\User;
use App\Modules\Leave\Models\CompOffRequest;
use Illuminate\Auth\Access\Response;
use App\Core\Constants\PermissionConstants;

class CompOffPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            PermissionConstants::VIEW_COMP_OFF,
            PermissionConstants::MANAGE_COMP_OFF,
            PermissionConstants::VIEW_OWN_COMP_OFF,
        ]);
    }

    public function manage(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_COMP_OFF);
    }

    public function view(User $user, CompOffRequest $compOffRequest): bool
    {
        if ($compOffRequest->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasPermissionTo(PermissionConstants::MANAGE_COMP_OFF)) {
            return true;
        }

        if ($user->hasPermissionTo(PermissionConstants::VIEW_OWN_COMP_OFF)) {
            return $user->employee?->id === $compOffRequest->employee_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::CREATE_COMP_OFF);
    }

    public function update(User $user, CompOffRequest $compOffRequest): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_COMP_OFF) && $user->employee?->id !== $compOffRequest->employee_id;
    }

    public function delete(User $user, CompOffRequest $compOffRequest): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_COMP_OFF);
    }
}
