<?php

namespace App\Modules\Leave\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Modules\Leave\Models\LeaveRequest;
use App\Modules\Leave\Models\CompOffRequest;
use App\Modules\Leave\Models\Holiday;
use App\Modules\Leave\Policies\LeavePolicy;
use App\Modules\Leave\Policies\CompOffPolicy;
use App\Modules\Leave\Policies\HolidayPolicy;

class LeaveAuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        LeaveRequest::class => LeavePolicy::class,
        CompOffRequest::class => CompOffPolicy::class,
        Holiday::class => HolidayPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
