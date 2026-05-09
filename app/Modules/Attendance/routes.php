<?php

use App\Modules\Attendance\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::name('attendance.')->middleware(['auth'])->group(function () {
    Route::get('kiosk', [\App\Modules\Attendance\Controllers\ClockController::class, 'kiosk'])->name('kiosk');
    Route::post('clock-in', [\App\Modules\Attendance\Controllers\ClockController::class, 'clockIn'])->name('clock-in');
    Route::post('clock-out', [\App\Modules\Attendance\Controllers\ClockController::class, 'clockOut'])->name('clock-out');

    // Logs access: Everyone can see their own, Admin/Manager can see all
    Route::get('logs', [AttendanceController::class, 'index'])->name('index');
    Route::get('logs/{log}', [AttendanceController::class, 'show'])->name('show');

    Route::middleware(['role:tadmin|tmanager'])->group(function () {
        Route::get('settings', [\App\Modules\Attendance\Controllers\ClockController::class, 'settings'])->name('settings');
        Route::post('settings', [\App\Modules\Attendance\Controllers\ClockController::class, 'saveSettings'])->name('settings.save');

        // Other admin-only log actions
        Route::get('logs/create', [AttendanceController::class, 'create'])->name('create');
        Route::post('logs', [AttendanceController::class, 'store'])->name('store');
        Route::get('logs/{log}/edit', [AttendanceController::class, 'edit'])->name('edit');
        Route::put('logs/{log}', [AttendanceController::class, 'update'])->name('update');
        Route::delete('logs/{log}', [AttendanceController::class, 'destroy'])->name('destroy');

        Route::get('shifts/assignments', [\App\Modules\Attendance\Controllers\ShiftAssignmentController::class, 'index'])->name('shifts.assignments');
        Route::post('shifts/assignments', [\App\Modules\Attendance\Controllers\ShiftAssignmentController::class, 'update'])->name('shifts.assignments.update');
    });
});
