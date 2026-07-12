<?php

use App\SaaS\Tenancy\TenantContext;

if (!function_exists('saas_tenant')) {
    function saas_tenant($attribute = null) {
        $tenant = app(TenantContext::class)->getTenant();
        if (!$tenant) return null;
        if ($attribute) return $tenant->{$attribute} ?? null;
        return $tenant;
    }
}

if (!function_exists('tenant')) {
    function tenant($attribute = null) {
        return saas_tenant($attribute);
    }
}
