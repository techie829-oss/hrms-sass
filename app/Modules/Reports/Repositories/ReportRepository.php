<?php

namespace App\Modules\Reports\Repositories;

use App\Modules\Reports\Interfaces\ReportRepositoryInterface;
use App\Modules\HR\Models\Employee;
use App\Modules\HR\Models\Department;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Leave\Models\LeaveRequest;
use App\Modules\Payroll\Models\PayrollRun;
use App\Modules\Payroll\Models\Payslip;
use App\Modules\Reports\Models\Report;
use App\Core\BaseRepository;
use Illuminate\Support\Facades\DB;

class ReportRepository extends BaseRepository implements ReportRepositoryInterface
{
    public function __construct(Report $model)
    {
        $this->model = $model;
    }

    public function getWorkforceStats()
    {
        return [
            'totalEmployees' => Employee::active()->count(),
            'departments' => Department::withCount('employees')->get(),
            'genderDistribution' => Employee::active()
                ->select('gender', DB::raw('count(*) as count'))
                ->groupBy('gender')
                ->get(),
            'employmentTypeDistribution' => Employee::active()
                ->select('employment_type', DB::raw('count(*) as count'))
                ->groupBy('employment_type')
                ->get()
        ];
    }

    public function getAttendanceMetrics(string $startDate, string $endDate)
    {
        $baseQuery = clone AttendanceLog::query();
        
        return [
            'totalLogs' => AttendanceLog::whereBetween('date', [$startDate, $endDate])->count(),
            'presentCount' => AttendanceLog::whereBetween('date', [$startDate, $endDate])->where('status', 'present')->count(),
            'absentCount' => AttendanceLog::whereBetween('date', [$startDate, $endDate])->where('status', 'absent')->count(),
            'lateCount' => AttendanceLog::whereBetween('date', [$startDate, $endDate])->where('is_late', true)->count(),
            'avgWorkedHours' => round(AttendanceLog::whereBetween('date', [$startDate, $endDate])->avg('worked_hours') ?? 0, 2),
            'totalOvertimeMinutes' => AttendanceLog::whereBetween('date', [$startDate, $endDate])->sum('overtime_minutes'),
            'totalLateMinutes' => AttendanceLog::whereBetween('date', [$startDate, $endDate])->where('is_late', true)->sum('late_minutes'),
        ];
    }

    public function getEmployeeAttendanceStats(string $startDate, string $endDate)
    {
        return Employee::active()
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
    }

    public function getDepartmentPunctuality(string $startDate, string $endDate)
    {
        return Department::withCount('employees')
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
    }

    public function getLeaveCount(string $startDate, string $endDate)
    {
        return LeaveRequest::where('status', 'approved')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate]);
            })->count();
    }

    public function getPayrollRun(int $month, int $year)
    {
        return PayrollRun::where('month', $month)->where('year', $year)->first();
    }

    public function getRecentPayrollRuns(int $limit = 6)
    {
        return PayrollRun::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }

    public function getPayslipsForRun(int $runId)
    {
        return Payslip::where('payroll_run_id', $runId)
            ->with('employee.department')
            ->get();
    }
}
