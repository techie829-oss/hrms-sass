<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\SaaS\Services\ActivityService;
use Illuminate\Http\Request;
use App\SaaS\Modules\ModuleManager;
use App\Modules\HR\Models\Employee;
use App\Modules\HR\Models\Department;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Leave\Models\LeaveRequest;
use App\Modules\Payroll\Models\PayrollRun;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct(
        protected ActivityService $activityService,
        protected ModuleManager $moduleManager
    ) {}

    public function index()
    {
        $tenantId = tenant('id');
        $recentActivities = $this->activityService->getRecentActivities(5);

        $data = [
            'recentActivities' => $recentActivities,
            'totalEmployees' => 0,
            'activeEmployees' => 0,
            'attendanceRate' => 0,
            'pendingLeaves' => 0,
            'payrollDisbursed' => 0,
            'recentEmployees' => collect([]),
            'departmentDistribution' => collect([]),
            'hasHr' => $this->moduleManager->tenantHasAccess('hr', $tenantId),
            'hasAttendance' => $this->moduleManager->tenantHasAccess('attendance', $tenantId),
            'hasLeave' => $this->moduleManager->tenantHasAccess('leave', $tenantId),
            'hasPayroll' => $this->moduleManager->tenantHasAccess('payroll', $tenantId),
            'currentUserAttendance' => null,
            'assignedShift' => null,
        ];

        if ($data['hasHr']) {
            $data['totalEmployees'] = Employee::count();
            $data['activeEmployees'] = Employee::where('status', 'active')->count();
            $data['recentEmployees'] = Employee::with('department')->latest('created_at')->take(5)->get();
            
            $departments = Department::withCount('employees')->get();
            $data['departmentDistribution'] = $departments->map(function($dept) use ($data) {
                $percentage = $data['totalEmployees'] > 0 ? round(($dept->employees_count / $data['totalEmployees']) * 100) : 0;
                return [
                    'name' => $dept->name,
                    'count' => $dept->employees_count,
                    'percentage' => $percentage
                ];
            })->filter(fn($d) => $d['count'] > 0)->sortByDesc('count')->take(4);
        }

        if ($data['hasAttendance'] && $data['totalEmployees'] > 0) {
            $today = Carbon::today();
            $presentCount = AttendanceLog::whereDate('date', $today)
                                         ->whereNotNull('check_in')
                                         ->distinct('employee_id')
                                         ->count('employee_id');
            $data['attendanceRate'] = round(($presentCount / $data['totalEmployees']) * 100, 1);

            // Fetch current user's attendance for the clocking widget
            if (auth()->user()->employee) {
                $employee = auth()->user()->employee;
                $data['currentUserAttendance'] = AttendanceLog::with('shift')->where('employee_id', $employee->id)
                    ->whereDate('date', $today)
                    ->first();
                $data['assignedShift'] = $employee->attendanceShift;
            }
        }

        $data['pendingTasks'] = collect([]);

        if ($data['hasLeave']) {
            $data['pendingLeaves'] = LeaveRequest::where('status', 'pending')->count();
            $pendingLeavesRecords = LeaveRequest::with('employee')->where('status', 'pending')->latest()->take(3)->get();
            
            foreach ($pendingLeavesRecords as $leave) {
                $name = $leave->employee ? current(explode(' ', $leave->employee->first_name)) : 'Employee';
                $isUrgent = Carbon::parse($leave->start_date)->isPast() || Carbon::parse($leave->start_date)->copy()->addDays(2)->isPast();
                $data['pendingTasks']->push([
                    'title' => "Approve {$name}'s Leave",
                    'urgent' => $isUrgent,
                    'is_completed' => false
                ]);
            }
        }

        if ($data['hasPayroll']) {
            $data['payrollDisbursed'] = PayrollRun::where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_net');
        }

        return view('dashboard', $data);
    }
}
