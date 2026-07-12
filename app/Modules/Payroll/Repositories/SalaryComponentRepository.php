<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Payroll\Interfaces\SalaryComponentRepositoryInterface;
use App\Core\BaseRepository;
use App\Modules\Payroll\Models\SalaryComponent;

class SalaryComponentRepository extends BaseRepository implements SalaryComponentRepositoryInterface
{
    public function __construct(SalaryComponent $model)
    {
        $this->model = $model;
    }

    public function getAllOrdered()
    {
        return SalaryComponent::orderBy('display_order')->get();
    }

    public function getActiveOrdered()
    {
        return SalaryComponent::where('is_active', true)->orderBy('display_order')->get();
    }

    public function createComponent(array $data)
    {
        return SalaryComponent::create($data);
    }

    public function getAllKeyedByCode()
    {
        return SalaryComponent::all()->keyBy('code');
    }
}
