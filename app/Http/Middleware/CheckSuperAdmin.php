<?php

namespace App\Http\Middleware;

use App\Core\Constants\RoleConstants;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow ANY internal SaaS user (SADMIN, SMANAGER, SSTAFF) to enter the domain.
        // Specific permissions (like deleting a tenant) should be checked via Gates in the controllers.
        if (! $request->user() || ! $request->user()->hasAnyRole(RoleConstants::getCentralRoles())) {
            abort(403, 'Unauthorized access to SaaS Administration context.');
        }

        return $next($request);
    }
}
