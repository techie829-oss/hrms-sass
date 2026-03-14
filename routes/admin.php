<?php

use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TenantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for the super admin panel (central app).
| These routes are NOT tenant-scoped.
|
*/

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Tenant Management
    Route::resource('tenants', TenantController::class)->except(['edit', 'update']);
    Route::patch('/tenants/{tenant}/toggle-status', [TenantController::class, 'toggleStatus'])->name('tenants.toggle-status');

    // Plan Management
    Route::resource('plans', PlanController::class)->only(['index', 'edit', 'update']);

    // Module Management
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::post('/modules/sync', [ModuleController::class, 'sync'])->name('modules.sync');

    // Role & Permission Management
    Route::resource('roles', RoleController::class);
});
