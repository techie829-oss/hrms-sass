<?php

use App\Modules\Reports\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:tadmin|tmanager'])->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/workforce', [ReportController::class, 'workforce'])->name('reports.workforce');
    Route::get('/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('/payroll', [ReportController::class, 'payroll'])->name('reports.payroll');
});
