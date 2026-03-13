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
            'max_employees' => 10,
            'max_modules' => 3,
            'price_monthly' => 0,
            'price_yearly' => 0,
        ],
        'starter' => [
            'name' => 'Starter',
            'max_employees' => 50,
            'max_modules' => 5,
            'price_monthly' => 999,
            'price_yearly' => 9990,
        ],
        'professional' => [
            'name' => 'Professional',
            'max_employees' => 200,
            'max_modules' => -1, // unlimited
            'price_monthly' => 2999,
            'price_yearly' => 29990,
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'max_employees' => -1, // unlimited
            'max_modules' => -1,
            'price_monthly' => 7999,
            'price_yearly' => 79990,
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
