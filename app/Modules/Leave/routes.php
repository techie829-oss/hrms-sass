<?php

use Illuminate\Support\Facades\Route;

Route::prefix('leave')->name('leave.')->middleware(['auth'])->group(function () {
    // Route::resource('applications', LeaveApplicationController::class);
    // Route::post('{id}/approve', [LeaveApplicationController::class, 'approve'])->name('approve');
    // Route::post('{id}/reject', [LeaveApplicationController::class, 'reject'])->name('reject');
});
