<?php

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
    Route::get('/tenants', function () {
        return view('admin.tenants.index');
    })->name('tenants.index');

    // Plan Management
    Route::get('/plans', function () {
        return view('admin.plans.index');
    })->name('plans.index');

    // Module Management
    Route::get('/modules', function () {
        return view('admin.modules.index');
    })->name('modules.index');
});
