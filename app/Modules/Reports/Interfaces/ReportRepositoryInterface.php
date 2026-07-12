<?php

namespace App\Modules\Reports\Interfaces;

use App\Core\BaseRepositoryInterface;

interface ReportRepositoryInterface extends BaseRepositoryInterface
{
    public function getWorkforceStats();
    public function getAttendanceMetrics(string $startDate, string $endDate);
    public function getEmployeeAttendanceStats(string $startDate, string $endDate);
    public function getDepartmentPunctuality(string $startDate, string $endDate);
    public function getLeaveCount(string $startDate, string $endDate);
    public function getPayrollRun(int $month, int $year);
    public function getRecentPayrollRuns(int $limit = 6);
    public function getPayslipsForRun(int $runId);
}
