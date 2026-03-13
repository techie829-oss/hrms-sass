<?php

use Illuminate\Support\Facades\Route;

Route::prefix('performance')->name('performance.')->middleware(['auth'])->group(function () {
    // Route::resource('reviews', ReviewController::class);
    // Route::resource('goals', GoalController::class);
});
