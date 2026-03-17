<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Recruitment\Controllers\JobPostingController;
use App\Modules\Recruitment\Controllers\JobApplicationController;

Route::name('recruitment.')->middleware(['auth', 'role:tadmin|tmanager'])->group(function () {
    Route::get('/', [JobPostingController::class, 'index'])->name('dashboard');
    Route::resource('job_postings', JobPostingController::class);

    // Applications
    Route::get('applications', [JobApplicationController::class, 'index'])->name('applications.index');
    Route::get('applications/{application}', [JobApplicationController::class, 'show'])->name('applications.show');
    Route::post('applications/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('applications.status');
    Route::post('applications/{application}/interview', [JobApplicationController::class, 'scheduleInterview'])->name('applications.interview.schedule');
    Route::post('interviews/{interview}', [JobApplicationController::class, 'updateInterview'])->name('interviews.update');
    Route::post('applications/{application}/hire', [JobApplicationController::class, 'hire'])->name('applications.hire');
});
