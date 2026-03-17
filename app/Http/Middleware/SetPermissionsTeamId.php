<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetPermissionsTeamId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (tenant()) {
            setPermissionsTeamId(tenant('id'));
        } else {
            // For SaaS Internal context, we use null team ID
            setPermissionsTeamId(null);
        }

        return $next($request);
    }
}
