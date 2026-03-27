<?php

use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\SaaS\RazorpayController;
use App\Http\Controllers\SaaS\WebhookController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Foundation: Central Host Identification
$centralHost = parse_url(config('app.url'), PHP_URL_HOST) ?? config('app.url');

// 2. Global Authentication Routes (Available on ALL domains)
// This ensures route('login') always uses the current request's host.
Route::middleware(['web'])->group(function () {
    require __DIR__.'/auth.php';
});

// 3. Central Landing Site (hrms.test)
Route::domain($centralHost)->middleware(['web'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('central.home');

    Route::get('/pricing', fn() => view('pricing'));
    Route::get('/modules', fn() => view('modules'));
    Route::get('/about', fn() => view('about'));
    Route::get('/contact', fn() => view('contact'));

    // Tenant Selection Hub
    Route::middleware(['auth'])->get('/hub', [\App\Http\Controllers\SaaS\TenantHubController::class, 'index'])->name('saas.hub');

    Route::post('/webhooks/razorpay', [WebhookController::class, 'handle']);
});

// 4. Super Admin Panel (app.hrms.test)
Route::domain('app.' . $centralHost)->middleware(['web', 'auth', 'superadmin', 'scope.roles'])->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('super-admin.dashboard');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    Route::resource('tenants', TenantController::class)->names('admin.tenants');
    Route::patch('tenants/{tenant}/toggle-status', [TenantController::class, 'toggleStatus'])->name('admin.tenants.toggle-status');
    Route::patch('tenants/{tenant}/update-plan', [TenantController::class, 'updatePlan'])->name('admin.tenants.update-plan');
    Route::patch('tenants/{tenant}/toggle-module', [TenantController::class, 'toggleModule'])->name('admin.tenants.toggle-module');

    Route::resource('plans', \App\Http\Controllers\Admin\PlanController::class)->only(['index', 'edit', 'update'])->names('admin.plans');
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->only(['index', 'create', 'store'])->names('admin.roles');
    Route::post('/modules/sync', [ModuleController::class, 'sync'])->name('admin.modules.sync');
});

// 5. Global Profile Routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 6. Tenant Subdomain & General Fallback (handled by CustomIdentifyTenant middleware)
Route::middleware(['web'])->group(function () {
    
    // Catch-all for Root Domain logic
    Route::get('/', function () {
        if (function_exists('saas_tenant') && saas_tenant()) {
            if (auth()->check()) {
                return redirect()->route('tenant.dashboard');
            }
            return redirect()->route('login');
        }
        
        // If no tenant context and not on central domain, redirect to central home
        return redirect()->route('central.home');
    })->name('tenant.home');

    Route::middleware(['auth', 'tenant.active'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('tenant.dashboard');
        
        // Additional tenant application routes go here...
    });
});
