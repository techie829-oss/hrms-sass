<?php

use App\Http\Middleware\CheckModuleAccess;
use App\Http\Middleware\CheckSuperAdmin;
use App\Http\Middleware\EnsureTenantIsActive;
use App\Http\Middleware\ResolveTenantSchema;
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
        $middleware->append(ResolveTenantSchema::class);
        $middleware->alias([
            'tenant.active' => EnsureTenantIsActive::class,
            'module.access' => CheckModuleAccess::class,
            'super_admin' => CheckSuperAdmin::class,
            'scope.roles' => \App\Http\Middleware\SetPermissionsTeamId::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/webhooks/razorpay',
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            if (tenant()) {
                return route('tenant.login');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
