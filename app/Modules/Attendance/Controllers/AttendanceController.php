<?php

namespace App\Modules\Attendance\Controllers;

use App\Core\BaseController;
use App\Modules\Attendance\Services\AttendanceService;
use Illuminate\Http\Request;
use App\Modules\Attendance\Models\AttendanceLog;

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
        $canViewAll = $user->can('view_all_attendance');
        $canViewOwn = $user->can('view_own_attendance');

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
        $summaryQuery = \App\Modules\Attendance\Models\AttendanceDailySummary::query();
        
        if (isset($filters['employee_id'])) {
            $summaryQuery->where('employee_id', $filters['employee_id']);
        }
        
        if (isset($filters['month'])) {
            $carbon = \Carbon\Carbon::parse($filters['month']);
            $summaryQuery->whereYear('date', $carbon->year)->whereMonth('date', $carbon->month);
        } else {
            $summaryQuery->whereYear('date', date('Y'))->whereMonth('date', date('m'));
        }

        $summaries = $summaryQuery->get();

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
    public function store(Request $request)
    {
        $this->authorize('create', AttendanceLog::class);
        
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'date' => ['required', 'date'],
            'check_in' => ['nullable', 'date_format:H:i'],
            'remarks' => ['nullable', 'string'],
        ]);

        $this->attendanceService->checkIn($validated['employee_id'], $validated);

        return redirect()->route('attendance.index')
            ->with('success', 'Check-in recorded successfully.');
    }

    /**
     * Display a single attendance log.
     */
    public function show(string $id)
    {
        $log = AttendanceLog::with('employee')->findOrFail($id);
        $this->authorize('view', $log);

        // Fetch all logs for this employee on this date
        $allLogs = AttendanceLog::where('employee_id', $log->employee_id)
            ->where('date', $log->date)
            ->orderBy('check_in', 'asc')
            ->get();

        $user = auth()->user();
        $canViewAll = $user->can('view_all_attendance');

        return view('attendance::show', compact('log', 'allLogs', 'canViewAll'));
    }
}
