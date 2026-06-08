<?php

use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\SaaS\RazorpayController;
use App\Http\Controllers\SaaS\WebhookController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Foundation: Central Host Identification
$centralHost = parse_url(config('app.url'), PHP_URL_HOST) ?? config('app.url');

// 2. Removed Global Authentication Routes from here to disable central domain login

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
Route::domain('app.' . $centralHost)->middleware(['web'])->group(function () {
    // Auth routes for Super Admin
    require __DIR__.'/auth.php';
    
    Route::get('/force-logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    });
});

Route::domain('app.' . $centralHost)->middleware(['web', 'auth', 'scope.roles', 'superadmin'])->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('super-admin.dashboard');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    Route::resource('tenants', TenantController::class)->names('admin.tenants');
    Route::post('tenants/{tenant}/restore', [TenantController::class, 'restore'])->name('admin.tenants.restore');
    Route::delete('tenants/{tenant}/force-delete', [TenantController::class, 'forceDelete'])->name('admin.tenants.force-delete');
    Route::patch('tenants/{tenant}/toggle-status', [TenantController::class, 'toggleStatus'])->name('admin.tenants.toggle-status');
    Route::patch('tenants/{tenant}/update-plan', [TenantController::class, 'updatePlan'])->name('admin.tenants.update-plan');
    Route::patch('tenants/{tenant}/toggle-module', [TenantController::class, 'toggleModule'])->name('admin.tenants.toggle-module');
    Route::get('tenants/{tenant}/checkout/{plan:slug}', [RazorpayController::class, 'checkout'])->name('admin.tenants.checkout');
    Route::post('tenants/{tenant}/verify/{plan:slug}', [RazorpayController::class, 'verify'])->name('admin.tenants.verify');

    Route::resource('plans', \App\Http\Controllers\Admin\PlanController::class)->only(['index', 'edit', 'update'])->names('admin.plans');
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->names('admin.roles');
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class)->names('admin.permissions');
    Route::get('/modules', [ModuleController::class, 'index'])->name('admin.modules.index');
    Route::post('/modules/sync', [ModuleController::class, 'sync'])->name('admin.modules.sync');
    Route::get('/modules/visualizer', [ModuleController::class, 'visualizer'])->name('admin.modules.visualizer');
    Route::get('/modules/{slug}', [ModuleController::class, 'show'])->name('admin.modules.show');
});

// 5. Global Profile Routes
Route::middleware(['web', 'auth', 'scope.roles'])->group(function () {
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
