<?php

use Illuminate\Support\Facades\Route;

Route::prefix('payroll')->name('payroll.')->middleware(['auth'])->group(function () {
    // Route::resource('payslips', PayslipController::class);
    // Route::post('generate/{month}', [PayrollController::class, 'generate'])->name('generate');
});
