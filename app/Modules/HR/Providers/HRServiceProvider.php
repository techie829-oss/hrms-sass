<?php

namespace App\Modules\HR\Providers;

use App\Modules\HR\Interfaces\DepartmentRepositoryInterface;
use App\Modules\HR\Interfaces\DesignationRepositoryInterface;
use App\Modules\HR\Interfaces\EmployeeRepositoryInterface;
use App\Modules\HR\Repositories\DepartmentRepository;
use App\Modules\HR\Repositories\DesignationRepository;
use App\Modules\HR\Repositories\EmployeeRepository;
use Illuminate\Support\ServiceProvider;

class HRServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(DesignationRepositoryInterface::class, DesignationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load module routes, views, etc. if needed here
    }
}
