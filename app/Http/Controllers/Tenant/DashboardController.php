<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\SaaS\Services\ActivityService;
use Illuminate\Http\Request;
use App\SaaS\Modules\ModuleManager;
use App\Modules\HR\Models\Employee;
use App\Modules\HR\Models\Department;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\AttendanceEmployeeEnforcement;
use App\Modules\Attendance\Models\AttendanceRoleEnforcement;
use App\Modules\Attendance\Models\AttendancePolicy;
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
        
        // Granular Permissions (Now automatically true for Admins via Gate::before)
        $canViewEmployees = $user->can('view_employees');
        $canViewAttendance = $user->can('view_attendance');
        $canViewLeave = $user->can('view_leave');
        $canViewPayroll = $user->can('view_payroll');
        $canApproveLeave = $user->can('approve_leave');
        
        $recentActivities = ($canViewEmployees || $canViewAttendance || $canViewLeave) 
            ? $this->activityService->getRecentActivities(5) 
            : collect([]);

        $data = [
            'canViewEmployees' => $canViewEmployees,
            'canViewAttendance' => $canViewAttendance,
            'canViewLeave' => $canViewLeave,
            'canViewPayroll' => $canViewPayroll,
            'canApproveLeave' => $canApproveLeave,
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
            'hasRecruitment' => $this->moduleManager->tenantHasAccess('recruitment', $tenantId),
            'hasOperations' => $this->moduleManager->tenantHasAccess('operations', $tenantId),
            'hasPerformance' => $this->moduleManager->tenantHasAccess('performance', $tenantId),
            'hasReports' => $this->moduleManager->tenantHasAccess('reports', $tenantId),
            'dashboardRoute' => 'tenant.dashboard',
            'currentUserAttendance' => null,
            'assignedShift' => null,
        ];

        if ($data['hasHr'] && $canViewEmployees) {
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
            if ($canViewAttendance) {
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
                
                $policy = $employee->attendancePolicy;
                
                // Resolve Multi-Clocking
                $multiClocking = 0; 
                $empEnf = AttendanceEmployeeEnforcement::where('employee_id', $employee->id)->first();
                
                if ($empEnf && $empEnf->multi_clocking != 0) {
                    $multiClocking = $empEnf->multi_clocking;
                } else {
                    $roleIds = $user->roles()->pluck('id')->toArray();
                    $roleEnf = AttendanceRoleEnforcement::whereIn('role_id', $roleIds)
                        ->where('multi_clocking', '!=', 0)
                        ->first();
                        
                    if ($roleEnf) {
                        $multiClocking = $roleEnf->multi_clocking;
                    } else if ($policy) {
                        $multiClocking = $policy->allow_multi_clocking ? 1 : 2;
                    }
                }
                $data['isMultiEnabled'] = ($multiClocking == 1);

                // Resolve Enforcement
                $enforcement = 0;
                if ($empEnf && $empEnf->enforce_kiosk != 0) {
                    $enforcement = $empEnf->enforce_kiosk;
                } else {
                    $roleIds = $user->roles()->pluck('id')->toArray();
                    $roleEnf = AttendanceRoleEnforcement::whereIn('role_id', $roleIds)
                        ->where('enforce_kiosk', '!=', 0)
                        ->first();
                        
                    if ($roleEnf) {
                        $enforcement = $roleEnf->enforce_kiosk;
                    } else if ($policy) {
                        $enforcement = $policy->enforce_clockin ? 1 : 2;
                    }
                }
                
                $isSecure = request()->secure() || in_array(request()->getHost(), ['localhost', '127.0.0.1']) || str_ends_with(request()->getHost(), '.test');
                $data['enforceKiosk'] = ($enforcement == 1 && $isSecure);
                
                $data['myRecentAttendance'] = AttendanceLog::where('employee_id', $employee->id)
                    ->latest('date')
                    ->take(5)
                    ->get();
            }
        }

        $data['pendingTasks'] = collect([]);

        if ($data['hasLeave']) {
            if ($canApproveLeave) {
                // Admin/Manager view: Total pending in company
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
            } else if ($user->employee && $user->can('view_own-leave')) {
                // Staff view: Their own pending requests
                $data['pendingLeaves'] = LeaveRequest::where('employee_id', $user->employee->id)->where('status', 'pending')->count();
            }
        }

        if ($data['hasPayroll'] && $canViewPayroll) {
            $data['payrollDisbursed'] = PayrollRun::where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_net');
        }

        return view('tenant.dashboard', $data);
    }
}
