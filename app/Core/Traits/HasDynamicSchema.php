<?php

namespace App\Core\Traits;

use Illuminate\Support\Facades\Schema;

/**
 * Trait HasDynamicSchema
 * 
 * This trait allows an Eloquent model to dynamically point to a specific 
 * PostgreSQL schema based on the current tenant's 'schema' configuration.
 * It bypasses the need for search_path magic and provides explicit isolation.
 */
trait HasDynamicSchema
{
    /**
     * Get the table associated with the model, 
     * prefixed with the tenant-specific schema if applicable.
     */
    public function getTable()
    {
        $baseTable = parent::getTable();
        
        // If there's already a schema prefix (contains a dot), return as is
        if (str_contains($baseTable, '.')) {
            return $baseTable;
        }

        // Use custom TenantContext
        $context = app(\App\SaaS\Tenancy\TenantContext::class);
        if ($context->isActive()) {
            $schema = $context->getSchema();
            return $schema . '.' . $baseTable;
        }

        // Default to public
        return 'public.' . $baseTable;
    }

    /**
     * Ensure the model uses the default connection
     */
    public function getConnectionName()
    {
        return config('database.default');
    }
}
