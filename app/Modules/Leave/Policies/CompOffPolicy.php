<?php

namespace App\Modules\Leave\Policies;

use App\Models\User;
use App\Modules\Leave\Models\CompOffRequest;
use Illuminate\Auth\Access\Response;

class CompOffPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['view comp_off', 'manage comp_off']);
    }

    public function manage(User $user): bool
    {
        return $user->hasPermissionTo('manage comp_off');
    }

    public function view(User $user, CompOffRequest $compOffRequest): bool
    {
        if ($compOffRequest->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasPermissionTo('manage comp_off')) {
            return true;
        }

        return $user->employee?->id === $compOffRequest->employee_id;
    }

    public function create(User $user): bool
    {
        return true; // Any employee can request comp-off if they worked. 
        // Or restricted: return $user->hasPermissionTo('create comp_off');
    }

    public function update(User $user, CompOffRequest $compOffRequest): bool
    {
        return $user->hasPermissionTo('manage comp_off') && $user->employee?->id !== $compOffRequest->employee_id;
    }

    public function delete(User $user, CompOffRequest $compOffRequest): bool
    {
        return $user->hasPermissionTo('manage comp_off');
    }
}
