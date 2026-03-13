<?php

namespace App\Http\Middleware;

use App\SaaS\Tenancy\TenantResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantSchema
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = TenantResolver::resolveFromRequest($request);

        if ($tenant) {
            // Initialize Stancl Tenancy context if not already done
            if (! tenant()) {
                tenancy()->initialize($tenant);
            }

            $currentSchema = $tenant->schema ?? 'shared';

            // DYNAMIC SCHEMA SWITCH:
            // For dedicated mode: search_path = tenant_acme, shared, public
            // For shared mode: search_path = shared, public
            if ($tenant->mode === 'dedicated') {
                DB::statement("SET search_path TO {$currentSchema}, shared, public");
            } else {
                DB::statement('SET search_path TO shared, public');
            }
        } else {
            // Central app context
            DB::statement('SET search_path TO public');
        }

        return $next($request);
    }
}
