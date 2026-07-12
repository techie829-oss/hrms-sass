<?php

use App\Modules\Leave\Controllers\LeaveRequestController;
use App\Modules\Leave\Controllers\LeaveTypeController;
use App\Modules\Leave\Controllers\HolidayController;
use App\Modules\Leave\Controllers\CompOffController;
use App\Core\Constants\PermissionConstants;
use Illuminate\Support\Facades\Route;

Route::name('leave.')->middleware(['auth', 'tenant.active', 'scope.roles', 'module.access:leave'])->group(function () {
    Route::post('requests/bulk', [LeaveRequestController::class, 'bulkStore'])->name('requests.bulk');
    Route::resource('requests', LeaveRequestController::class);
    
    // Holidays
    Route::get('holidays', [HolidayController::class, 'index'])->name('holidays.index');
    Route::post('holidays', [HolidayController::class, 'store'])->name('holidays.store');
    Route::delete('holidays/{holiday}', [HolidayController::class, 'destroy'])->name('holidays.destroy');

    // Comp-Off
    Route::get('comp-off', [CompOffController::class, 'index'])->name('comp-off.index');
    Route::post('comp-off', [CompOffController::class, 'store'])->name('comp-off.store');
    Route::post('comp-off/bulk-grant', [CompOffController::class, 'bulkGrant'])->name('comp-off.bulk-grant');
    Route::post('comp-off/one-click-settle', [CompOffController::class, 'settleBulk'])->name('comp-off.one-click-settle');
    Route::post('comp-off/{compOffRequest}/status', [CompOffController::class, 'updateStatus'])->name('comp-off.status');

    Route::post('requests/{leaveRequest}/status', [LeaveRequestController::class, 'updateStatus'])->name('requests.status');

    Route::prefix('settings')->name('types.')->group(function () {
        Route::get('leave-types', [LeaveTypeController::class, 'index'])->name('index');
        Route::post('leave-types', [LeaveTypeController::class, 'store'])->name('store');
    });
});
