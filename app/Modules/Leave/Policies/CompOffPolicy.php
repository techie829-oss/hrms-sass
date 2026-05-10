<?php

namespace App\Modules\Leave\Policies;

use App\Models\User;
use App\Modules\Leave\Models\CompOffRequest;
use Illuminate\Auth\Access\Response;

class CompOffPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['view-comp-off', 'manage-comp-off', 'view-own-comp-off']);
    }

    public function manage(User $user): bool
    {
        return $user->hasPermissionTo('manage-comp-off');
    }

    public function view(User $user, CompOffRequest $compOffRequest): bool
    {
        if ($compOffRequest->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasPermissionTo('manage-comp-off')) {
            return true;
        }

        if ($user->hasPermissionTo('view-own-comp-off')) {
            return $user->employee?->id === $compOffRequest->employee_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-comp-off');
    }

    public function update(User $user, CompOffRequest $compOffRequest): bool
    {
        return $user->hasPermissionTo('manage-comp-off') && $user->employee?->id !== $compOffRequest->employee_id;
    }

    public function delete(User $user, CompOffRequest $compOffRequest): bool
    {
        return $user->hasPermissionTo('manage-comp-off');
    }
}
