<?php

namespace App\Modules\Leave\Policies;

use App\Models\User;
use App\Modules\Leave\Models\Holiday;
use App\Core\Constants\PermissionConstants;

class HolidayPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::VIEW_HOLIDAYS);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_HOLIDAYS);
    }

    public function update(User $user, Holiday $holiday): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_HOLIDAYS);
    }

    public function delete(User $user, Holiday $holiday): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_HOLIDAYS);
    }
}
