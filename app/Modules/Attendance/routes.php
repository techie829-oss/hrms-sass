<?php

use App\Modules\Attendance\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::name('attendance.')->middleware(['auth'])->group(function () {
    Route::get('kiosk', [\App\Modules\Attendance\Controllers\ClockController::class, 'kiosk'])->name('kiosk');
    Route::post('clock-in', [\App\Modules\Attendance\Controllers\ClockController::class, 'clockIn'])->name('clock-in');
    Route::post('clock-out', [\App\Modules\Attendance\Controllers\ClockController::class, 'clockOut'])->name('clock-out');

    Route::middleware(['role:tadmin|tmanager'])->group(function () {
        Route::resource('logs', AttendanceController::class)->names([
            'index' => 'index',
            'create' => 'create',
            'store' => 'store',
            'show' => 'show',
            'edit' => 'edit',
            'update' => 'update',
            'destroy' => 'destroy',
        ]);

        Route::get('shifts/assignments', [\App\Modules\Attendance\Controllers\ShiftAssignmentController::class, 'index'])->name('shifts.assignments');
        Route::post('shifts/assignments', [\App\Modules\Attendance\Controllers\ShiftAssignmentController::class, 'update'])->name('shifts.assignments.update');
    });
});
