<?php

namespace App\Modules\Payroll\Interfaces;

use App\Core\BaseRepositoryInterface;

interface PayrollRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateRuns(int $perPage = 10);
    public function createRun(array $data);
    public function getPayslips(int $runId, int $perPage = 20);
    public function generatePayslips(int $runId): int;
}
