<?php

use Illuminate\Support\Facades\Route;

Route::prefix('attendance')->name('attendance.')->middleware(['auth'])->group(function () {
    // Route::resource('records', AttendanceController::class);
    // Route::post('check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
    // Route::post('check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
});
