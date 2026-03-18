<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'tenant.active', 'scope.roles', 'module.access:operations', 'role:tadmin|tmanager'])->group(function () {
    
    // Lead Management
    Route::resource('leads', \App\Modules\Operations\Controllers\LeadController::class);

    // Contact Management
    Route::resource('contacts', \App\Modules\Operations\Controllers\ContactController::class);

    // Project Management
    Route::resource('projects', \App\Modules\Operations\Controllers\ProjectController::class);

    // Standalone Task Management
    Route::resource('tasks', \App\Modules\Operations\Controllers\TaskController::class);

    // Task Management (Nested)
    Route::resource('projects.tasks', \App\Modules\Operations\Controllers\TaskController::class)->except(['index']);

    // Client Management (CRM Lite)
    Route::resource('clients', \App\Modules\Operations\Controllers\ClientController::class);

    // Timesheets
    Route::get('timesheets', [\App\Modules\Operations\Controllers\TimesheetController::class, 'index'])->name('timesheets.index');
    Route::post('timesheets', [\App\Modules\Operations\Controllers\TimesheetController::class, 'store'])->name('timesheets.store');

});
