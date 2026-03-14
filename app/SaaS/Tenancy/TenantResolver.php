<?php

namespace App\SaaS\Tenancy;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantResolver
{
    /**
     * Resolve the tenant based on the request.
     */
    public static function resolveFromRequest(Request $request): ?Tenant
    {
        $host = $request->getHost();

        // 1. Check central domains (skip if it matches central)
        $centralDomains = config('tenancy.central_domains', []);

        foreach ($centralDomains as $domain) {
            if ($host === $domain || str_ends_with($host, '.'.$domain)) {
                if (str_starts_with($host, 'app.')) {
                    return null;
                }
            }
        }

        if (in_array($host, $centralDomains)) {
            return null;
        }

        // 2. Resolve by domain column (Exact match)
        $tenant = Tenant::where('domain', $host)->first();
        if ($tenant) {
            return $tenant;
        }

        // 3. Resolve by Subdomain (Slug)
        // Extract the first part of the domain (e.g., 'company1' from 'company1.hrms.com')
        $parts = explode('.', $host);
        if (count($parts) >= 2) {
            $subdomain = $parts[0];

            // Exclude 'www' or any other specific non-tenant subdomains if needed
            if (in_array($subdomain, ['www', 'app', 'admin'])) {
                return null;
            }

            return Tenant::where('slug', $subdomain)->first();
        }

        return null;
    }
}
