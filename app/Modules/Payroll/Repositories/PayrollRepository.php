<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Payroll\Interfaces\PayrollRepositoryInterface;
use App\Modules\Payroll\Models\PayrollRun;
use App\Modules\Payroll\Models\Payslip;
use App\Modules\Payroll\Models\SalaryStructure;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Leave\Models\LeaveRequest;
use Carbon\Carbon;
use App\Core\BaseRepository;
use Illuminate\Support\Facades\DB;

class PayrollRepository extends BaseRepository implements PayrollRepositoryInterface
{
    public function __construct(PayrollRun $model)
    {
        $this->model = $model;
    }

    public function paginateRuns(int $perPage = 10)
    {
        return PayrollRun::latest()->paginate($perPage);
    }

    public function createRun(array $data)
    {
        $title = Carbon::createFromDate($data['year'], $data['month'], 1)->format('F Y') . " Payroll";
        
        return PayrollRun::create(array_merge($data, [
            'title' => $title,
            'status' => 'draft',
        ]));
    }

    public function getPayslips(int $runId, int $perPage = 20)
    {
        $run = PayrollRun::findOrFail($runId);
        return $run->payslips()->with('employee')->paginate($perPage);
    }

    public function generatePayslips(int $runId): int
    {
        $run = PayrollRun::findOrFail($runId);
        $startDate = Carbon::createFromDate($run->year, $run->month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        return DB::transaction(function () use ($run, $startDate, $endDate, $daysInMonth) {
            $run->payslips()->delete();

            $activeStructures = SalaryStructure::with('employee')
                ->where('is_active', true)
                ->get();

            $activeEmployees = \App\Modules\HR\Models\Employee::active()->get();

            $totalNet = 0;
            $count = 0;

            foreach ($activeEmployees as $employee) {
                $structure = $activeStructures->firstWhere('employee_id', $employee->id);

                $grossSalary = $structure ? $structure->gross_salary : ($employee->basic_salary ?? 0);
                $netSalary = $structure ? $structure->net_salary : ($employee->basic_salary ?? 0);

                if ($netSalary <= 0 && $grossSalary <= 0) {
                    continue;
                }

                $absentDays = $this->calculateAbsentDays($employee->id, $startDate, $endDate);

                $perDaySalary = $netSalary / $daysInMonth;
                $lopAmount = $absentDays * $perDaySalary;

                $payableNet = max(0, $netSalary - $lopAmount);
                $totalNet += $payableNet;

                Payslip::create([
                    'tenant_id' => saas_tenant('id'),
                    'payroll_run_id' => $run->id,
                    'employee_id' => $employee->id,
                    'salary_structure_id' => $structure?->id,
                    'gross_salary' => $grossSalary,
                    'net_salary' => $payableNet,
                    'total_earnings' => $grossSalary,
                    'total_deductions' => abs($netSalary - $grossSalary) + $lopAmount,
                    'is_paid' => false,
                    'remarks' => $absentDays > 0 ? "LOP deducted for {$absentDays} days." : ($structure ? null : "Generated from basic salary fallback"),
                ]);

                $count++;
            }

            $run->update([
                'total_net' => $totalNet,
                'status' => 'completed'
            ]);

            return $count;
        });
    }

    private function calculateAbsentDays($employeeId, $startDate, $endDate)
    {
        $attendanceDays = AttendanceLog::where('employee_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('status', ['present', 'late', 'half_day'])
            ->count();

        $approvedLeaveDays = LeaveRequest::where('employee_id', $employeeId)
            ->where('status', 'approved')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate]);
            })
            ->count();

        $totalWorkDays = $startDate->diffInDays($endDate) + 1;
        
        $weekends = 0;
        $tempDate = $startDate->copy();
        while($tempDate->lte($endDate)) {
            if ($tempDate->isWeekend()) $weekends++;
            $tempDate->addDay();
        }

        $absent = $totalWorkDays - $attendanceDays - $approvedLeaveDays - $weekends;
        
        return max(0, $absent);
    }
}
