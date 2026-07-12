<?php

use App\Modules\Payroll\Controllers\PayrollController;
use App\Modules\Payroll\Controllers\SalaryComponentController;
use App\Modules\Payroll\Controllers\SalaryStructureController;
use App\Core\Constants\PermissionConstants;
use Illuminate\Support\Facades\Route;

Route::name('payroll.')->middleware(['auth', 'tenant.active', 'scope.roles', 'module.access:payroll'])->group(function () {
    Route::get('/payslips/{payslip}/download', [PayrollController::class, 'download'])->name('payslip.download');

    Route::get('/', [PayrollController::class, 'index'])->name('index');
    Route::get('/create', [PayrollController::class, 'create'])->name('create');
    Route::post('/', [PayrollController::class, 'store'])->name('store');
    Route::get('/{run}', [PayrollController::class, 'show'])->name('show');
    Route::post('/{run}/generate', [PayrollController::class, 'generate'])->name('generate');

    Route::prefix('settings')->group(function () {
        Route::get('components', [SalaryComponentController::class, 'index'])->name('components.index');
        Route::post('components', [SalaryComponentController::class, 'store'])->name('components.store');
        Route::put('components/{component}', [SalaryComponentController::class, 'update'])->name('components.update');
        Route::delete('components/{component}', [SalaryComponentController::class, 'destroy'])->name('components.destroy');
    });

    Route::resource('salary-structures', SalaryStructureController::class)->names('salary_structures');
});
