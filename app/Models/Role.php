<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * Always use the central connection for Roles, 
     * even when a tenant context is active.
     */
    public function getConnectionName()
    {
        return config('tenancy.database.central_connection') ?: config('database.default');
    }
}
