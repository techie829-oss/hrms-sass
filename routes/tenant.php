<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'tenant.active',
    'auth', // Require login even on subdomains
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('tenant.dashboard');

    Route::get('/', function () {
        return redirect()->route('tenant.dashboard');
    });

    // HR Module Routes
    Route::middleware('module.access:hr')->group(function () {
        Route::get('/hr', function () {
            return 'HR Module Dashboard';
        });
    });

    // Attendance Module Routes
    Route::middleware('module.access:attendance')->group(function () {
        Route::get('/attendance', function () {
            return 'Attendance Module Dashboard';
        });
    });

    require __DIR__.'/auth.php';
});
