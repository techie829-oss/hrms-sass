<?php

namespace App\Modules\Payroll\Interfaces;

use App\Core\BaseRepositoryInterface;

interface SalaryStructureRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllWithEmployee();
    public function deactivateActiveStructuresForEmployee(int $employeeId, string $effectiveToDate);
    public function createStructure(array $data);
}
