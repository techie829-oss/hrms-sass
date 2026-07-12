<?php

namespace App\Modules\Reports\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Reports\Interfaces\ReportRepositoryInterface;
use App\Modules\Reports\Repositories\ReportRepository;

class ReportsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
    }
}
