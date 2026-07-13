<?php

use App\Modules\Attendance\Controllers\AttendanceController;
use App\Modules\Attendance\Controllers\ClockController;
use App\Modules\Attendance\Controllers\ShiftAssignmentController;
use App\Core\Constants\PermissionConstants;
use Illuminate\Support\Facades\Route;

Route::name('attendance.')->middleware(['auth', 'tenant.active', 'scope.roles', 'module.access:attendance'])->group(function () {
    Route::get('kiosk', [ClockController::class, 'kiosk'])->name('kiosk');
    Route::post('clock-in', [ClockController::class, 'clockIn'])->name('clock-in');
    Route::post('clock-out', [ClockController::class, 'clockOut'])->name('clock-out');

    // Logs access: Everyone can see their own, Admin/Manager can see all
    Route::get('logs', [AttendanceController::class, 'index'])->name('index');
    Route::get('logs/{log}', [AttendanceController::class, 'show'])->name('show');

    Route::get('settings', [ClockController::class, 'settings'])->name('settings');
    Route::post('settings', [ClockController::class, 'saveSettings'])->name('settings.save');
    Route::post('shifts', [ClockController::class, 'storeShift'])->name('shifts.store');
    Route::delete('shifts/{shift}', [ClockController::class, 'deleteShift'])->name('shifts.delete');

    // Other admin-only log actions
    Route::get('logs/create', [AttendanceController::class, 'create'])->name('create');
    Route::post('logs', [AttendanceController::class, 'store'])->name('store');
    Route::delete('logs/{log}', [AttendanceController::class, 'destroy'])->name('destroy');

    Route::get('shifts/assignments', [ShiftAssignmentController::class, 'index'])->name('shifts.assignments');
    Route::post('shifts/assignments', [ShiftAssignmentController::class, 'update'])->name('shifts.assignments.update');
});
