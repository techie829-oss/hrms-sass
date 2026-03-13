<?php

namespace App\Http\Middleware;

use App\SaaS\Modules\ModuleManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $tenantId = tenant('id');

        if (! $tenantId) {
            return $next($request);
        }

        $moduleManager = app(ModuleManager::class);

        if (! $moduleManager->tenantHasAccess($module, $tenantId)) {
            abort(403, "The '".ucfirst($module)."' module is not enabled for your account.");
        }

        return $next($request);
    }
}
