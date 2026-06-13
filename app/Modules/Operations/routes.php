<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'tenant.active', 'scope.roles', 'module.access:operations'])->group(function () {
    
    // Lead Management
    Route::resource('leads', \App\Modules\Operations\Controllers\LeadController::class)->only(['index', 'show'])->middleware('permission:view_leads');
    Route::resource('leads', \App\Modules\Operations\Controllers\LeadController::class)->except(['index', 'show'])->middleware('permission:manage_leads');

    // Contact Management
    Route::resource('contacts', \App\Modules\Operations\Controllers\ContactController::class)->only(['index', 'show'])->middleware('permission:view_leads');
    Route::resource('contacts', \App\Modules\Operations\Controllers\ContactController::class)->except(['index', 'show'])->middleware('permission:manage_leads');

    // Project Management
    Route::resource('projects', \App\Modules\Operations\Controllers\ProjectController::class)->only(['index', 'show'])->middleware('permission:view_projects');
    Route::resource('projects', \App\Modules\Operations\Controllers\ProjectController::class)->except(['index', 'show'])->middleware('permission:manage_projects');

    // Standalone Task Management
    Route::resource('tasks', \App\Modules\Operations\Controllers\TaskController::class)->only(['index', 'show'])->middleware('permission:view_tasks');
    Route::resource('tasks', \App\Modules\Operations\Controllers\TaskController::class)->except(['index', 'show'])->middleware('permission:manage_tasks');

    // Task Management (Nested)
    Route::resource('projects.tasks', \App\Modules\Operations\Controllers\TaskController::class)->only(['show'])->middleware('permission:view_tasks');
    Route::resource('projects.tasks', \App\Modules\Operations\Controllers\TaskController::class)->except(['index', 'show'])->middleware('permission:manage_tasks');

    // Client Management (CRM Lite)
    Route::resource('clients', \App\Modules\Operations\Controllers\ClientController::class)->only(['index', 'show'])->middleware('permission:view_leads');
    Route::resource('clients', \App\Modules\Operations\Controllers\ClientController::class)->except(['index', 'show'])->middleware('permission:manage_leads');

    // Timesheets
    Route::get('timesheets', [\App\Modules\Operations\Controllers\TimesheetController::class, 'index'])->name('timesheets.index')->middleware('permission:view_timesheet|view_own_timesheet');
    Route::post('timesheets', [\App\Modules\Operations\Controllers\TimesheetController::class, 'store'])->name('timesheets.store')->middleware('permission:manage_timesheet|view_own_timesheet');

});
