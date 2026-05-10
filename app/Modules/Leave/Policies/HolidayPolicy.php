<?php

namespace App\Modules\Leave\Policies;

use App\Models\User;
use App\Modules\Leave\Models\Holiday;

class HolidayPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view holidays');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage holidays');
    }

    public function update(User $user, Holiday $holiday): bool
    {
        return $user->hasPermissionTo('manage holidays');
    }

    public function delete(User $user, Holiday $holiday): bool
    {
        return $user->hasPermissionTo('manage holidays');
    }
}
