<?php

namespace App\SaaS\Tenancy;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

/**
 * TenantContext
 * 
 * Custom singleton to manage the active tenant state and PostgreSQL session schema.
 */
class TenantContext
{
    protected ?Tenant $tenant = null;

    /**
     * Set the current tenant and initialize the PostgreSQL session.
     */
    public function setTenant(?Tenant $tenant): void
    {
        $this->tenant = $tenant;

        if ($tenant && $tenant->schema) {
            // SET search_path for the CURRENT SESSION as a fallback
            // But we primarily use the HasDynamicSchema trait for model queries.
            DB::statement("SET search_path TO \"{$tenant->schema}\", public");
        } else {
            DB::statement("SET search_path TO public");
        }
    }

    /**
     * Get the current tenant model.
     */
    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    /**
     * Get the current schema name.
     */
    public function getSchema(): string
    {
        return $this->tenant->schema ?? 'public';
    }

    /**
     * Check if a tenant context is active.
     */
    public function isActive(): bool
    {
        return $this->tenant !== null;
    }
}
