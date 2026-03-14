<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
*/

$centralDomains = config('tenancy.central_domains', []);

foreach ($centralDomains as $centralDomain) {
    Route::domain('{tenant}.' . $centralDomain)->middleware([
        'web',
        InitializeTenancyByDomain::class,
        PreventAccessFromCentralDomains::class,
        'tenant.active',
        'auth',
    ])->group(function () {
        
        Route::get('/dashboard', [\App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('tenant.dashboard');

        Route::get('/', function () {
            return redirect()->route('tenant.dashboard');
        });

        // HR Module Routes
        Route::middleware('module.access:hr')->group(function () {
            Route::get('/hr', function () {
                return 'HR Module Dashboard';
            });
        });

        require __DIR__.'/auth.php';
    });
}
