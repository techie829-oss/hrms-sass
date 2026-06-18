<?php

// Billing Configuration
return [

    /*
    |--------------------------------------------------------------------------
    | Plans
    |--------------------------------------------------------------------------
    */

    'plans' => [
        'free' => [
            'name' => 'Free',
            'description' => 'For very small teams getting started.',
            'max_employees' => 10,
            'max_modules' => 3,
            'price_monthly' => 0,
            'price_yearly' => 0,
            'features' => [
                '10 Employees',
                '3 Modules'
            ]
        ],
        'starter' => [
            'name' => 'Starter',
            'description' => 'The essential tools for growing teams.',
            'max_employees' => 50,
            'max_modules' => 5,
            'price_monthly' => 999,
            'price_yearly' => 9990,
            'features' => [
                '50 Employees',
                '5 Modules'
            ]
        ],
        'professional' => [
            'name' => 'Professional',
            'description' => 'Complete automation for larger companies.',
            'max_employees' => 200,
            'max_modules' => -1,
            'price_monthly' => 2999,
            'price_yearly' => 29990,
            'features' => [
                '200 Employees',
                'Unlimited Modules'
            ],
            'is_popular' => true
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'description' => 'Advanced features with no limits.',
            'max_employees' => -1, // unlimited
            'max_modules' => -1,
            'price_monthly' => 7999,
            'price_yearly' => 79990,
            'features' => [
                'Unlimited Employees',
                'Unlimited Modules'
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Trial Settings
    |--------------------------------------------------------------------------
    */

    'trial' => [
        'enabled' => true,
        'days' => 14,
        'plan' => 'professional',
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    */

    'currency' => env('BILLING_CURRENCY', 'INR'),
    'currency_symbol' => env('BILLING_CURRENCY_SYMBOL', '₹'),
];
