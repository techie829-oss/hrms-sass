<?php

use Illuminate\Support\Facades\Route;

Route::prefix('recruitment')->name('recruitment.')->middleware(['auth'])->group(function () {
    // Route::resource('jobs', JobPostingController::class);
    // Route::resource('applications', JobApplicationController::class);
});
