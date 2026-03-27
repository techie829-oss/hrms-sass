<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = saas_tenant();

        if (! $tenant) {
            return $next($request);
        }

        // Check 'status' column (active, inactive, suspended)
        if (isset($tenant->status) && $tenant->status !== 'active') {
            abort(403, 'Your account is currently '.$tenant->status.'. Please contact support.');
        }

        return $next($request);
    }
}
