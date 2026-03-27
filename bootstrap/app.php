<?php

// Load Custom multi-tenancy helpers early
require_once __DIR__.'/../app/SaaS/helpers.php';

use App\Http\Middleware\CheckModuleAccess;
use App\Http\Middleware\CheckSuperAdmin;
use App\Http\Middleware\EnsureTenantIsActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Use our custom, trait-based schema isolation middleware (Prepend for early identification)
        $middleware->prepend(\App\Http\Middleware\CustomIdentifyTenant::class);
        $middleware->alias([
            'tenant.active' => EnsureTenantIsActive::class,
            'tenant.employee' => \App\Http\Middleware\EnsureEmployeeProfile::class,
            'module.access' => CheckModuleAccess::class,
            'superadmin' => CheckSuperAdmin::class,
            'scope.roles' => \App\Http\Middleware\SetPermissionsTeamId::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/webhooks/razorpay',
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            return route('login');
        });

        $middleware->redirectUsersTo(function ($request) {
            $user = $request->user();
            if ($user && $user->hasRole(\App\Core\Constants\RoleConstants::SADMIN)) {
                return route('super-admin.dashboard');
            }
            if (function_exists('saas_tenant') && saas_tenant()) {
                return route('tenant.dashboard');
            }
            return route('saas.hub'); // Redirect to Tenant Hub on central domain
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
