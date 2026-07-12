<?php

namespace App\Modules\Attendance\Controllers;

use App\Core\BaseController;
use App\Modules\Attendance\Services\AttendanceService;
use Illuminate\Http\Request;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Core\Constants\PermissionConstants;
use App\Modules\Attendance\Requests\StoreAttendanceLogRequest;
use App\Modules\Attendance\DTOs\AttendanceLogData;

class AttendanceController extends BaseController
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    /**
     * Display attendance logs.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $canViewAll = $user->can(PermissionConstants::VIEW_ATTENDANCE);
        $canViewOwn = $user->can(PermissionConstants::VIEW_OWN_ATTENDANCE);

        if (!$canViewAll && !$canViewOwn) {
            abort(403, 'You do not have permission to view attendance logs.');
        }
        
        $filters = $request->only(['employee_id', 'date', 'status', 'search', 'month']);

        // If cannot view all, force filter by their own employee ID
        if (!$canViewAll) {
            if (!$user->employee) {
                return back()->with('error', 'Employee profile not found.');
            }
            $filters['employee_id'] = $user->employee->id;
        }

        $view = $request->get('view');
        
        // Dynamic default view: 
        // If viewing specific employee OR viewing own only -> default to calendar
        // If viewing all employees -> default to list
        if (!$view) {
            $view = (isset($filters['employee_id']) || !$canViewAll) ? 'calendar' : 'list';
        }

        $isCalendar = $view === 'calendar';
        $perPage = $isCalendar ? 1000 : 15;
        
        $logs = $this->attendanceService->paginate($perPage, $filters);

        // Fetch Daily Summaries for the current month/view
        $summaries = $this->attendanceService->getSummaryQuery($filters);

        return view('attendance::index', compact('logs', 'summaries', 'canViewAll', 'view', 'filters'));
    }

    /**
     * Show check-in form (simple for now).
     */
    public function create()
    {
        $this->authorize('create', AttendanceLog::class);
        return view('attendance::create');
    }

    /**
     * Store a new check-in.
     */
    public function store(StoreAttendanceLogRequest $request)
    {
        $dto = AttendanceLogData::fromRequest($request->validated());

        $this->attendanceService->checkIn($dto->employee_id, $dto->toArray());

        return redirect()->route('attendance.index')
            ->with('success', 'Check-in recorded successfully.');
    }

    /**
     * Display a single attendance log.
     */
    public function show(string $id)
    {
        $log = $this->attendanceService->getLogById($id);
        $this->authorize('view', $log);

        // Fetch all logs for this employee on this date
        $allLogs = $this->attendanceService->getLogsByEmployeeAndDate($log->employee_id, $log->date);

        $user = auth()->user();
        $canViewAll = $user->can(PermissionConstants::VIEW_ALL_ATTENDANCE);

        return view('attendance::show', compact('log', 'allLogs', 'canViewAll'));
    }
}
