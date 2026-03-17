<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /**
     * Always use the central connection for Permissions, 
     * even when a tenant context is active.
     */
    public function getConnectionName()
    {
        return config('tenancy.database.central_connection') ?: config('database.default');
    }
}
