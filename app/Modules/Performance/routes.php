<?php

use App\Modules\Performance\Controllers\PerformanceController;
use App\Modules\Performance\Controllers\KPIController;
use App\Modules\Performance\Controllers\AppraisalController;
use App\Modules\Performance\Controllers\GoalController;
use App\Core\Constants\PermissionConstants;
use Illuminate\Support\Facades\Route;

Route::name('performance.')->middleware(['auth', 'tenant.active', 'scope.roles', 'module.access:performance'])->group(function () {
    Route::get('/', [PerformanceController::class, 'index'])->name('dashboard');
    
    Route::resource('kpis', KPIController::class)->only(['index', 'store']);
    Route::resource('appraisals', AppraisalController::class)->only(['index', 'store', 'update']);
    Route::resource('goals', GoalController::class)->only(['index', 'store', 'update']);
});
