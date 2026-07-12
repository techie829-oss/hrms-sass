<?php

namespace App\Modules\Payroll\Services;

use App\Core\BaseService;
use App\Modules\Payroll\Interfaces\SalaryComponentRepositoryInterface;
use App\Modules\Payroll\DTOs\SalaryComponentData;

/**
 * @property SalaryComponentRepositoryInterface $repository
 */
class SalaryComponentService extends BaseService
{
    public function __construct(SalaryComponentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllOrdered()
    {
        return $this->repository->getAllOrdered();
    }

    public function getActiveOrdered()
    {
        return $this->repository->getActiveOrdered();
    }

    public function createComponent(SalaryComponentData $data)
    {
        return $this->repository->createComponent([
            'name' => $data->name,
            'code' => $data->code,
            'type' => $data->type,
            'calculation_type' => $data->calculation_type,
            'default_amount' => $data->default_amount,
            'percentage_base' => $data->percentage_base,
            'is_taxable' => $data->is_taxable,
            'is_mandatory' => $data->is_mandatory,
            'display_order' => $data->display_order,
            'tenant_id' => saas_tenant('id'),
        ]);
    }

    public function getAllKeyedByCode()
    {
        return $this->repository->getAllKeyedByCode();
    }

    public function updateComponent($id, SalaryComponentData $data)
    {
        return $this->repository->update($id, [
            'name' => $data->name,
            'code' => $data->code,
            'type' => $data->type,
            'calculation_type' => $data->calculation_type,
            'default_amount' => $data->default_amount,
            'percentage_base' => $data->percentage_base,
            'is_taxable' => $data->is_taxable,
            'is_mandatory' => $data->is_mandatory,
            'display_order' => $data->display_order,
        ]);
    }

    public function deleteComponent($id)
    {
        return $this->repository->delete($id);
    }
}
