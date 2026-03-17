<?php

use App\Modules\Leave\Controllers\LeaveRequestController;
use App\Modules\Leave\Controllers\LeaveTypeController;
use Illuminate\Support\Facades\Route;

Route::name('leave.')->middleware(['auth'])->group(function () {
    Route::resource('requests', LeaveRequestController::class);
    
    Route::middleware(['role:tadmin|tmanager'])->group(function () {
        Route::post('requests/{leaveRequest}/status', [LeaveRequestController::class, 'updateStatus'])->name('requests.status');

        Route::prefix('settings')->name('types.')->group(function () {
            Route::get('leave-types', [LeaveTypeController::class, 'index'])->name('index');
            Route::post('leave-types', [LeaveTypeController::class, 'store'])->name('store');
        });
    });
});
