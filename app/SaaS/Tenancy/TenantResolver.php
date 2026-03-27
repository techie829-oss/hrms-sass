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
        $centralHost = parse_url(config('app.url'), PHP_URL_HOST);
        $centralDomains = [$centralHost, 'app.' . $centralHost, 'localhost', '127.0.0.1'];

        if (in_array($host, $centralDomains)) {
            return null;
        }

        // 2. Resolve by domain column (Exact match)
        $tenant = Tenant::where('domain', $host)->first();
        if ($tenant) {
            return $tenant;
        }

        // 3. Resolve by Subdomain (Slug)
        $parts = explode('.', $host);
        if (count($parts) >= 2) {
            $subdomain = $parts[0];

            if (in_array($subdomain, ['www', 'app', 'admin'])) {
                return null;
            }

            return Tenant::where('slug', $subdomain)->first();
        }

        return null;
    }
}
