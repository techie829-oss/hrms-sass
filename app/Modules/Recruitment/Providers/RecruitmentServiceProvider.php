<?php

namespace App\Modules\Recruitment\Providers;

use App\Modules\Recruitment\Interfaces\JobApplicationRepositoryInterface;
use App\Modules\Recruitment\Interfaces\JobPostingRepositoryInterface;
use App\Modules\Recruitment\Repositories\JobApplicationRepository;
use App\Modules\Recruitment\Repositories\JobPostingRepository;
use Illuminate\Support\ServiceProvider;

class RecruitmentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(JobPostingRepositoryInterface::class, JobPostingRepository::class);
        $this->app->bind(JobApplicationRepositoryInterface::class, JobApplicationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
