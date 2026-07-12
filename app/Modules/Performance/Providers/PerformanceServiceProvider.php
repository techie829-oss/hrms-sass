<?php

namespace App\Modules\Performance\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Performance\Repositories\AppraisalRepositoryInterface;
use App\Modules\Performance\Repositories\AppraisalRepository;
use App\Modules\Performance\Repositories\GoalRepositoryInterface;
use App\Modules\Performance\Repositories\GoalRepository;
use App\Modules\Performance\Repositories\KPIRepositoryInterface;
use App\Modules\Performance\Repositories\KPIRepository;

class PerformanceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AppraisalRepositoryInterface::class, AppraisalRepository::class);
        $this->app->bind(GoalRepositoryInterface::class, GoalRepository::class);
        $this->app->bind(KPIRepositoryInterface::class, KPIRepository::class);
    }
}
