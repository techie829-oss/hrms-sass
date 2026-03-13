<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Landing Site Routes (hrms.com / hrms.test)
Route::domain(config('app.url', 'hrms.com'))->group(function () {
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

    require __DIR__.'/auth.php';
});

// 2. Super Admin Routes (app.hrms.com / app.hrms.test)
Route::domain('app.'.config('app.url', 'hrms.com'))->group(function () {
    Route::get('/', function () {
        if (Auth::check() && Auth::user()->hasRole('super_admin')) {
            return redirect()->route('super-admin.dashboard');
        }
        return redirect()->route('login');
    });

    Route::middleware(['auth', 'super_admin'])->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('super-admin.dashboard');

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        });

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__.'/auth.php';
});

// 3. Fallback for Localhost / Development
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
