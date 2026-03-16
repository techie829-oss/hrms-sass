<?php

use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Landing Site Routes (hrms.com / hrms.test)
$centralHost = parse_url(config('app.url'), PHP_URL_HOST) ?? config('app.url');

Route::domain($centralHost)->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/pricing', function () {
        return view('pricing');
    });

    Route::get('/modules', function () {
        return view('modules');
    });

    Route::get('/about', function () {
        return view('about');
    });

    Route::get('/contact', function () {
        return view('contact');
    });

    // 3. Fallback for Central Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
});

// 2. Super Admin Routes (app.hrms.com / app.hrms.test)
Route::domain('app.' . $centralHost)->group(function () {
    Route::get('/', function () {
        return redirect('/login');
    });

    Route::middleware(['auth', 'super_admin'])->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('super-admin.dashboard');

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        });

        Route::resource('tenants', TenantController::class)->names('admin.tenants');
        Route::patch('tenants/{tenant}/toggle-status', [TenantController::class, 'toggleStatus'])->name('admin.tenants.toggle-status');

        Route::resource('plans', \App\Http\Controllers\Admin\PlanController::class)->only(['index', 'edit', 'update'])->names('admin.plans');

        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->only(['index', 'create', 'store'])->names('admin.roles');

        Route::get('/modules', [ModuleController::class, 'index'])->name('admin.modules.index');
        Route::post('/modules/sync', [ModuleController::class, 'sync'])->name('admin.modules.sync');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__.'/auth.php';
});

