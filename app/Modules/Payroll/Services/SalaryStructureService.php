<?php

namespace App\Modules\Payroll\Services;

use App\Core\BaseService;
use App\Modules\Payroll\Interfaces\SalaryStructureRepositoryInterface;
use App\Modules\Payroll\DTOs\SalaryStructureData;

/**
 * @property SalaryStructureRepositoryInterface $repository
 */
class SalaryStructureService extends BaseService
{
    public function __construct(SalaryStructureRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllWithEmployee()
    {
        return $this->repository->getAllWithEmployee();
    }

    public function createStructure(SalaryStructureData $data)
    {
        $grossSalary = array_sum($data->earnings);
        $totalDeductions = array_sum($data->deductions);
        $netSalary = $grossSalary - $totalDeductions;

        $this->repository->deactivateActiveStructuresForEmployee($data->employee_id, $data->effective_from);

        return $this->repository->createStructure([
            'tenant_id' => saas_tenant('id'),
            'employee_id' => $data->employee_id,
            'ctc' => $data->ctc,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'earnings' => $data->earnings,
            'deductions' => $data->deductions,
            'effective_from' => $data->effective_from,
            'is_active' => true,
        ]);
    }
}
