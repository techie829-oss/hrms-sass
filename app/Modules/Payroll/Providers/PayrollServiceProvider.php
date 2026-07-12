<?php

namespace App\Modules\Payroll\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Payroll\Interfaces\PayrollRepositoryInterface;
use App\Modules\Payroll\Repositories\PayrollRepository;
use App\Modules\Payroll\Interfaces\SalaryStructureRepositoryInterface;
use App\Modules\Payroll\Repositories\SalaryStructureRepository;
use App\Modules\Payroll\Interfaces\SalaryComponentRepositoryInterface;
use App\Modules\Payroll\Repositories\SalaryComponentRepository;

class PayrollServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PayrollRepositoryInterface::class, PayrollRepository::class);
        $this->app->bind(SalaryStructureRepositoryInterface::class, SalaryStructureRepository::class);
        $this->app->bind(SalaryComponentRepositoryInterface::class, SalaryComponentRepository::class);
    }
}
