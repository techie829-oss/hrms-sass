<?php

use App\Modules\Reports\Controllers\ReportController;
use App\Core\Constants\PermissionConstants;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'tenant.active', 'scope.roles', 'module.access:reports'])->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/workforce', [ReportController::class, 'workforce'])->name('reports.workforce');
    Route::get('/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('/payroll', [ReportController::class, 'payroll'])->name('reports.payroll');
});
