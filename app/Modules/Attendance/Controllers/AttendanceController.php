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

        $isCalendar = $request->get('view') === 'calendar';
        $perPage = $isCalendar ? 1000 : 15;
        
        $logs = $this->attendanceService->paginate($perPage, $filters);

        return view('attendance::index', compact('logs', 'canViewAll'));
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

        $user = auth()->user();
        $canViewAll = $user->can('view_all_attendance');

        return view('attendance::show', compact('log', 'canViewAll'));
    }
}
