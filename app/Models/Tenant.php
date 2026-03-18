<?php

namespace App\Models;

use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * Custom columns on the tenants table.
     * These will be stored directly in the tenants table.
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'slug',
            'domain',
            'schema',
            'mode',
            'plan_id',
            'status',
            'email',
            'contact_no',
        ];
    }

    /**
     * Get the subscription for this tenant.
     */
    public function subscription()
    {
        return $this->hasOne(\App\SaaS\Billing\Subscription::class, 'tenant_id');
    }
}
