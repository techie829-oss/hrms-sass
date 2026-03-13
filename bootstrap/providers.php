<?php

use App\Providers\AppServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\ModuleServiceProvider;
use App\Providers\TenancyServiceProvider;

return [
    AppServiceProvider::class,
    ModuleServiceProvider::class,
    HorizonServiceProvider::class,
    TenancyServiceProvider::class,
];
