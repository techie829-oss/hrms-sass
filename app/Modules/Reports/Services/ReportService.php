<?php

namespace App\Modules\Reports\Services;

use App\Core\BaseService;
use App\Modules\Reports\Interfaces\ReportRepositoryInterface;
use Carbon\Carbon;

/**
 * @property ReportRepositoryInterface $repository
 */
class ReportService extends BaseService
{
    public function __construct(ReportRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getWorkforceReport()
    {
        return $this->repository->getWorkforceStats();
    }

    public function getAttendanceReport(int $month, int $year)
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $workingDaysInMonth = $startDate->diffInWeekdays($endDate) + 1;

        $metrics = $this->repository->getAttendanceMetrics($startDate->toDateTimeString(), $endDate->toDateTimeString());
        $employeeAttendance = $this->repository->getEmployeeAttendanceStats($startDate->toDateTimeString(), $endDate->toDateTimeString());
        $departmentStats = $this->repository->getDepartmentPunctuality($startDate->toDateTimeString(), $endDate->toDateTimeString());
        $leaveCount = $this->repository->getLeaveCount($startDate->toDateTimeString(), $endDate->toDateTimeString());

        return [
            'month' => $month,
            'year' => $year,
            'workingDaysInMonth' => $workingDaysInMonth,
            'totalLogs' => $metrics['totalLogs'],
            'presentCount' => $metrics['presentCount'],
            'absentCount' => $metrics['absentCount'],
            'lateCount' => $metrics['lateCount'],
            'avgWorkedHours' => $metrics['avgWorkedHours'],
            'totalOvertimeMinutes' => $metrics['totalOvertimeMinutes'],
            'totalLateMinutes' => $metrics['totalLateMinutes'],
            'employeeAttendance' => $employeeAttendance,
            'departmentStats' => $departmentStats,
            'leaveCount' => $leaveCount
        ];
    }

    public function getPayrollReport(int $month, int $year)
    {
        $payrollRun = $this->repository->getPayrollRun($month, $year);
        $recentRuns = $this->repository->getRecentPayrollRuns(6);
        $payslips = collect();
        $departmentPayroll = collect();

        if ($payrollRun) {
            $payslips = $this->repository->getPayslipsForRun($payrollRun->id);
            
            if ($payslips->isNotEmpty()) {
                $departmentPayroll = $payslips->groupBy(function ($slip) {
                    return $slip->employee?->department?->name ?? 'Unassigned';
                })->map(function ($group, $deptName) {
                    return (object)[
                        'department' => $deptName,
                        'employee_count' => $group->count(),
                        'total_gross' => $group->sum('gross_earnings'),
                        'total_deductions' => $group->sum('total_deductions'),
                        'total_net' => $group->sum('net_salary'),
                    ];
                })->values();
            }
        }

        $totalEmployeesPaid = $payrollRun?->total_employees ?? 0;

        return [
            'month' => $month,
            'year' => $year,
            'payrollRun' => $payrollRun,
            'recentRuns' => $recentRuns,
            'payslips' => $payslips,
            'departmentPayroll' => $departmentPayroll,
            'totalGross' => $payrollRun?->total_gross ?? 0,
            'totalDeductions' => $payrollRun?->total_deductions ?? 0,
            'totalNet' => $payrollRun?->total_net ?? 0,
            'totalEmployeesPaid' => $totalEmployeesPaid,
            'avgSalary' => $totalEmployeesPaid > 0 ? round(($payrollRun?->total_net ?? 0) / $totalEmployeesPaid, 2) : 0
        ];
    }
}
