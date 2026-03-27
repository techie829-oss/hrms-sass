<?php

namespace App\Modules\Payroll\Services;

use App\Modules\Payroll\Models\SalaryStructure;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Leave\Models\LeaveRequest;
use App\Modules\Payroll\Models\PayrollRun;
use App\Modules\Payroll\Models\Payslip;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    /**
     * Generate payslips for an existing PayrollRun.
     */
    public function generatePayslips(PayrollRun $run)
    {
        $startDate = Carbon::createFromDate($run->year, $run->month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        return DB::transaction(function () use ($run, $startDate, $endDate, $daysInMonth) {
            // Delete existing payslips for this run if any
            $run->payslips()->delete();

            $activeStructures = SalaryStructure::with('employee')
                ->where('is_active', true)
                ->get();

            $totalNet = 0;
            $count = 0;

            foreach ($activeStructures as $structure) {
                $employee = $structure->employee;
                
                // Calculate LOP (Loss of Pay) days
                $absentDays = $this->calculateAbsentDays($employee->id, $startDate, $endDate);
                
                $perDaySalary = $structure->net_salary / $daysInMonth;
                $lopAmount = $absentDays * $perDaySalary;
                
                $payableNet = max(0, $structure->net_salary - $lopAmount);
                $totalNet += $payableNet;

                Payslip::create([
                    'tenant_id' => saas_tenant('id'),
                    'payroll_run_id' => $run->id,
                    'employee_id' => $employee->id,
                    'salary_structure_id' => $structure->id,
                    'gross_salary' => $structure->gross_salary,
                    'net_salary' => $payableNet,
                    'total_earnings' => $structure->gross_salary,
                    'total_deductions' => abs($structure->net_salary - $structure->gross_salary) + $lopAmount,
                    'is_paid' => false,
                    'remarks' => $absentDays > 0 ? "LOP deducted for {$absentDays} days." : null,
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

    /**
     * Calculate number of absent days (No attendance, No approved leave).
     */
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
