<?php

use App\Modules\HR\Controllers\DepartmentController;
use App\Modules\HR\Controllers\DesignationController;
use App\Modules\HR\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::name('hr.')->middleware(['auth', 'role:tadmin|tmanager'])->group(function () {
    Route::post('employees/{employee}/documents', [EmployeeController::class, 'uploadDocument'])->name('employees.documents.store');
    Route::get('employees/{employee}/documents/{document}/download', [EmployeeController::class, 'downloadDocument'])->name('employees.documents.download');
    Route::delete('employees/{employee}/documents/{document}', [EmployeeController::class, 'destroyDocument'])->name('employees.documents.destroy');
    Route::post('employees/{employee}/change-password', [EmployeeController::class, 'changePassword'])->name('employees.change-password');

    Route::resource('employees', EmployeeController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('designations', DesignationController::class);
});
