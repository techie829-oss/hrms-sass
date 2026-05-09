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
        $tenantId = saas_tenant('id');
        $user = auth()->user();
        $isAdmin = $user->hasRole(['tadmin', 'tmanager', 'superadmin']);
        
        $recentActivities = $isAdmin ? $this->activityService->getRecentActivities(5) : collect([]);

        $data = [
            'isAdmin' => $isAdmin,
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

        if ($data['hasHr'] && $isAdmin) {
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

        if ($data['hasAttendance']) {
            if ($isAdmin) {
                $totalEmp = Employee::count();
                if ($totalEmp > 0) {
                    $today = Carbon::today();
                    $presentCount = AttendanceLog::whereDate('date', $today)
                                                 ->whereNotNull('check_in')
                                                 ->distinct('employee_id')
                                                 ->count('employee_id');
                    $data['attendanceRate'] = round(($presentCount / $totalEmp) * 100, 1);
                }
            }

            // Fetch current user's attendance for the clocking widget (Available to all with an employee profile)
            if ($user->employee) {
                $employee = $user->employee;
                $data['currentUserAttendance'] = AttendanceLog::with('shift')->where('employee_id', $employee->id)
                    ->whereDate('date', Carbon::today())
                    ->latest()
                    ->first();
                $data['assignedShift'] = $employee->attendanceShift;
                
                // Check if clock-in enforcement is enabled for this employee's policy
                // Only enforce if we are in a secure context (HTTPS) as per user request
                $policy = $employee->attendancePolicy;
                $data['enforceKiosk'] = ($policy && $policy->enforce_clockin && request()->secure()) ? true : false;
                
                // Fetch recent attendance for the employee view
                $data['myRecentAttendance'] = AttendanceLog::where('employee_id', $employee->id)
                    ->latest('date')
                    ->take(5)
                    ->get();
            }
        }

        $data['pendingTasks'] = collect([]);

        if ($data['hasLeave']) {
            if ($isAdmin) {
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
            } else if ($user->employee) {
                // For regular employees, show their own pending leaves
                $data['pendingLeaves'] = LeaveRequest::where('employee_id', $user->employee->id)->where('status', 'pending')->count();
            }
        }

        if ($data['hasPayroll'] && $isAdmin) {
            $data['payrollDisbursed'] = PayrollRun::where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_net');
        }

        return view('dashboard', $data);
    }
}
