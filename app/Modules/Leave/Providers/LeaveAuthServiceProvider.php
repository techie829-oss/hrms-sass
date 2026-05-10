<?php

namespace App\Modules\Leave\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class LeaveAuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Modules\Leave\Models\LeaveRequest::class => \App\Modules\Leave\Policies\LeavePolicy::class,
        \App\Modules\Leave\Models\CompOffRequest::class => \App\Modules\Leave\Policies\CompOffPolicy::class,
        \App\Modules\Leave\Models\Holiday::class => \App\Modules\Leave\Policies\HolidayPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
