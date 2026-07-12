<?php

namespace App\Modules\Payroll\Services;

use App\Core\BaseService;
use App\Modules\Payroll\Interfaces\PayrollRepositoryInterface;
use App\Modules\Payroll\DTOs\PayrollRunData;

/**
 * @property PayrollRepositoryInterface $repository
 */
class PayrollService extends BaseService
{
    public function __construct(PayrollRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function paginateRuns(int $perPage = 10)
    {
        return $this->repository->paginateRuns($perPage);
    }

    public function createRun(PayrollRunData $data)
    {
        return $this->repository->createRun([
            'month' => $data->month,
            'year' => $data->year,
            'pay_date' => $data->pay_date,
            'notes' => $data->notes,
        ]);
    }

    public function getPayslips(int $runId, int $perPage = 20)
    {
        return $this->repository->getPayslips($runId, $perPage);
    }

    public function generatePayslips(int $runId)
    {
        return $this->repository->generatePayslips($runId);
    }
}
