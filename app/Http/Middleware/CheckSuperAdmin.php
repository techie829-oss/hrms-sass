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
        if (! $request->user() || ! $request->user()->hasRole(RoleConstants::SADMIN)) {
            abort(403, 'Unauthorized access to SaaS Administration context.');
        }

        return $next($request);
    }
}
