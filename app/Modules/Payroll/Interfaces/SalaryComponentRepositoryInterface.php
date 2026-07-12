<?php

namespace App\Modules\Payroll\Interfaces;

use App\Core\BaseRepositoryInterface;

interface SalaryComponentRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllOrdered();
    public function getActiveOrdered();
    public function createComponent(array $data);
    public function getAllKeyedByCode();
}
