<?php

// HRMS Configuration
return [

    /*
    |--------------------------------------------------------------------------
    | HRMS Application Settings
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'HRMS SaaS'),

    'modules' => [
        'hr',
        'attendance',
        'leave',
        'payroll',
        'recruitment',
        'performance',
        'reports',
    ],

    /*
    |--------------------------------------------------------------------------
    | Employee Settings
    |--------------------------------------------------------------------------
    */

    'employee' => [
        'id_prefix' => 'EMP',
        'id_length' => 6,
        'statuses' => ['active', 'inactive', 'on_leave', 'terminated'],
        'employment_types' => ['full_time', 'part_time', 'contract', 'intern'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Leave Settings
    |--------------------------------------------------------------------------
    */

    'leave' => [
        'max_casual_leave' => 12,
        'max_sick_leave' => 10,
        'max_earned_leave' => 15,
        'carry_forward' => true,
        'max_carry_forward_days' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Attendance Settings
    |--------------------------------------------------------------------------
    */

    'attendance' => [
        'work_start_time' => '09:00',
        'work_end_time' => '18:00',
        'late_threshold_minutes' => 15,
        'half_day_threshold_hours' => 4,
    ],

    /*
    |--------------------------------------------------------------------------
    | Payroll Settings
    |--------------------------------------------------------------------------
    */

    'payroll' => [
        'currency' => 'INR',
        'pay_cycle' => 'monthly', // monthly, bi-weekly, weekly
        'pay_day' => 1,
        'tax_calculation' => 'new_regime', // old_regime, new_regime
    ],
];
