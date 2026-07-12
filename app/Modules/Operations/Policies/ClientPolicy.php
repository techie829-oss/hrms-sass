<?php

namespace App\Modules\Operations\Policies;

use App\Models\User;
use App\Modules\Operations\Models\Client;
use App\Core\Constants\PermissionConstants;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::VIEW_LEADS) || $user->hasPermissionTo(PermissionConstants::MANAGE_LEADS);
    }

    public function view(User $user, Client $client): bool
    {
        if ($client->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo(PermissionConstants::VIEW_LEADS) || $user->hasPermissionTo(PermissionConstants::MANAGE_LEADS);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_LEADS);
    }

    public function update(User $user, Client $client): bool
    {
        if ($client->tenant_id !== $user->tenant_id) {
            return false;
        }
        return $user->hasPermissionTo(PermissionConstants::MANAGE_LEADS);
    }

    public function delete(User $user, Client $client): bool
    {
        if ($client->tenant_id !== $user->tenant_id) {
            return false;
        }
        return $user->hasPermissionTo(PermissionConstants::MANAGE_LEADS);
    }
}
