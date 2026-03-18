<?php

namespace App\Modules\Reports\Controllers;

use App\Core\BaseController;
use App\Modules\HR\Models\Employee;
use App\Modules\HR\Models\Department;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Leave\Models\LeaveRequest;
use App\Modules\Payroll\Models\PayrollRun;
use App\Modules\Payroll\Models\Payslip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends BaseController
{
    public function index()
    {
        return view('reports::index');
    }

    public function workforce()
    {
        $totalEmployees = Employee::active()->count();
        $departments = Department::withCount('employees')->get();
        
        $genderDistribution = Employee::active()
            ->select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->get();

        $employmentTypeDistribution = Employee::active()
            ->select('employment_type', DB::raw('count(*) as count'))
            ->groupBy('employment_type')
            ->get();

        return view('reports::workforce', compact(
            'totalEmployees',
            'departments',
            'genderDistribution',
            'employmentTypeDistribution'
        ));
    }

    public function attendance(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $workingDaysInMonth = $startDate->diffInWeekdays($endDate) + 1;

        // Overall Stats
        $totalLogs = AttendanceLog::whereBetween('date', [$startDate, $endDate])->count();
        $presentCount = AttendanceLog::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'present')->count();
        $absentCount = AttendanceLog::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'absent')->count();
        $lateCount = AttendanceLog::whereBetween('date', [$startDate, $endDate])
            ->where('is_late', true)->count();

        $totalWorkedHours = AttendanceLog::whereBetween('date', [$startDate, $endDate])
            ->sum('worked_hours');
        $avgWorkedHours = $totalLogs > 0 ? round($totalWorkedHours / max($presentCount, 1), 1) : 0;

        $totalOvertimeMinutes = AttendanceLog::whereBetween('date', [$startDate, $endDate])
            ->sum('overtime_minutes');
        $totalLateMinutes = AttendanceLog::whereBetween('date', [$startDate, $endDate])
            ->sum('late_minutes');

        // Per-Employee Breakdown
        $employeeAttendance = Employee::active()
            ->with('department')
            ->withCount([
                'attendanceLogs as present_days' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate])->where('status', 'present');
                },
                'attendanceLogs as absent_days' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate])->where('status', 'absent');
                },
                'attendanceLogs as late_days' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate])->where('is_late', true);
                },
            ])
            ->withSum([
                'attendanceLogs as total_hours' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                },
            ], 'worked_hours')
            ->withSum([
                'attendanceLogs as overtime_mins' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                },
            ], 'overtime_minutes')
            ->get();

        // Department-wise punctuality
        $departmentStats = Department::withCount('employees')
            ->get()
            ->map(function ($dept) use ($startDate, $endDate) {
                $empIds = $dept->employees()->pluck('id');
                $dept->dept_present = AttendanceLog::whereIn('employee_id', $empIds)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->where('status', 'present')->count();
                $dept->dept_late = AttendanceLog::whereIn('employee_id', $empIds)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->where('is_late', true)->count();
                return $dept;
            });

        // Leave requests for the month
        $leaveCount = LeaveRequest::where('status', 'approved')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate]);
            })->count();

        return view('reports::attendance', compact(
            'month', 'year', 'workingDaysInMonth',
            'totalLogs', 'presentCount', 'absentCount', 'lateCount',
            'avgWorkedHours', 'totalOvertimeMinutes', 'totalLateMinutes',
            'employeeAttendance', 'departmentStats', 'leaveCount'
        ));
    }

    public function payroll(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Payroll Run for selected period
        $payrollRun = PayrollRun::where('month', $month)->where('year', $year)->first();

        // All Payroll Runs for trend analysis (last 6 months)
        $recentRuns = PayrollRun::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        // Payslips for the selected period
        $payslips = collect();
        if ($payrollRun) {
            $payslips = Payslip::where('payroll_run_id', $payrollRun->id)
                ->with('employee.department')
                ->get();
        }

        // Department-wise salary breakdown
        $departmentPayroll = collect();
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

        // Summary stats
        $totalGross = $payrollRun?->total_gross ?? 0;
        $totalDeductions = $payrollRun?->total_deductions ?? 0;
        $totalNet = $payrollRun?->total_net ?? 0;
        $totalEmployeesPaid = $payrollRun?->total_employees ?? 0;
        $avgSalary = $totalEmployeesPaid > 0 ? round($totalNet / $totalEmployeesPaid, 2) : 0;

        return view('reports::payroll', compact(
            'month', 'year', 'payrollRun', 'recentRuns',
            'payslips', 'departmentPayroll',
            'totalGross', 'totalDeductions', 'totalNet',
            'totalEmployeesPaid', 'avgSalary'
        ));
    }
}
