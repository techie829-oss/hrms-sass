<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Models\Domain;
use App\SaaS\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class CustomIdentifyTenant
{
    /**
     * Handle an incoming request and identify the active tenant.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $context = app(TenantContext::class);

        // 1. Resolve domain from database
        $domain = Domain::where('domain', $host)->first();

        if ($domain) {
            $tenant = Tenant::withTrashed()->find($domain->tenant_id);
            if ($tenant) {
                if ($tenant->trashed()) {
                    abort(403, 'This account has been suspended or archived. Please contact support.');
                }

                // Initialize our custom context
                $context->setTenant($tenant);

                // Also Share with view
                view()->share('activeTenant', $tenant);
            }
        }

        return $next($request);
    }
}
