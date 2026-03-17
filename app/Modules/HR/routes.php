<?php

use App\Modules\HR\Controllers\DepartmentController;
use App\Modules\HR\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::name('hr.')->middleware(['auth', 'role:tadmin|tmanager'])->group(function () {
    Route::resource('employees', EmployeeController::class);
    Route::resource('departments', DepartmentController::class);
});
