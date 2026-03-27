<?php

namespace App\Models;

use App\Core\Traits\UsesPublicSchema;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use UsesPublicSchema;
    /**
     * Always use the central connection for Roles, 
     * even when a tenant context is active.
     */
    public function getConnectionName()
    {
        return config('tenancy.database.central_connection') ?: config('database.default');
    }
}
