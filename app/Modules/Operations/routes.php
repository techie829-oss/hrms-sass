<?php

use Illuminate\Support\Facades\Route;
use App\Core\Constants\PermissionConstants;
use App\Modules\Operations\Controllers\LeadController;
use App\Modules\Operations\Controllers\ContactController;
use App\Modules\Operations\Controllers\ProjectController;
use App\Modules\Operations\Controllers\TaskController;
use App\Modules\Operations\Controllers\ClientController;
use App\Modules\Operations\Controllers\TimesheetController;

Route::middleware(['auth', 'tenant.active', 'scope.roles', 'module.access:operations'])->group(function () {
    
    // Lead Management
    Route::resource('leads', LeadController::class);

    // Contact Management
    Route::resource('contacts', ContactController::class)->except(['show']);

    // Project Management
    Route::resource('projects', ProjectController::class)->except(['edit', 'update', 'destroy']);

    // Standalone Task Management
    Route::resource('tasks', TaskController::class);

    // Task Management (Nested)
    Route::resource('projects.tasks', TaskController::class)->except(['index']);

    // Client Management (CRM Lite)
    Route::resource('clients', ClientController::class)->only(['index', 'store']);

    // Timesheets
    Route::get('timesheets', [TimesheetController::class, 'index'])->name('timesheets.index');
    Route::post('timesheets', [TimesheetController::class, 'store'])->name('timesheets.store');

});
