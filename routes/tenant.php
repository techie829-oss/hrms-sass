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

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'tenant.active',
])->group(function () {
    
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('tenant.dashboard');

        Route::get('/', function () {
            return redirect()->route('tenant.dashboard');
        });

        // Module Access Protected Routes
        Route::middleware('module.access:hr')->prefix('hr')->group(function () {
            Route::get('/', function () {
                return view('modules.hr.index');
            })->name('hr.dashboard');
            
            require app_path('Modules/HR/routes.php');
        });

        Route::middleware('module.access:attendance')->prefix('attendance')->group(function () {
            require app_path('Modules/Attendance/routes.php');
        });

        Route::middleware('module.access:leave')->prefix('leave')->group(function () {
            require app_path('Modules/Leave/routes.php');
        });

        Route::middleware('module.access:payroll')->prefix('payroll')->group(function () {
            require app_path('Modules/Payroll/routes.php');
        });

        Route::middleware('module.access:performance')->prefix('performance')->group(function () {
            require app_path('Modules/Performance/routes.php');
        });

        Route::middleware('module.access:recruitment')->prefix('recruitment')->group(function () {
            require app_path('Modules/Recruitment/routes.php');
        });
        // Profile Routes (Tenant)
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('tenant.profile.edit');
        Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('tenant.profile.update');
        Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('tenant.profile.destroy');
    });

    Route::name('tenant.')->group(function () {
        require __DIR__.'/auth.php';
    });
});
