<?php

use App\Modules\Performance\Controllers\PerformanceController;
use App\Modules\Performance\Controllers\KPIController;
use App\Modules\Performance\Controllers\AppraisalController;
use App\Modules\Performance\Controllers\GoalController;
use Illuminate\Support\Facades\Route;

Route::name('performance.')->middleware(['auth'])->group(function () {
    Route::get('/', [PerformanceController::class, 'index'])->name('dashboard');
    
    Route::resource('kpis', KPIController::class);
    Route::resource('appraisals', AppraisalController::class);
    Route::resource('goals', GoalController::class);
});
