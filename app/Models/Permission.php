<?php

namespace App\Models;

use App\Core\Traits\UsesPublicSchema;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use UsesPublicSchema;
    /**
     * Always use the central connection for Permissions, 
     * even when a tenant context is active.
     */
    public function getConnectionName()
    {
        return config('tenancy.database.central_connection') ?: config('database.default');
    }

    /**
     * Root Fix: Always enforce slug format for permissions.
     * No spaces or underscores allowed in DB.
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = str_replace([' ', '_'], '-', strtolower($value));
    }
}
