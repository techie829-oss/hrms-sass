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
        $isAdmin = $user->hasRole(['tadmin', 'tmanager', 'superadmin']);
        
        $filters = $request->only(['employee_id', 'date', 'status']);

        // If not admin, force filter by their own employee ID
        if (!$isAdmin) {
            if (!$user->employee) {
                return back()->with('error', 'Employee profile not found.');
            }
            $filters['employee_id'] = $user->employee->id;
        }

        $logs = $this->attendanceService->paginate(15, $filters);

        return view('attendance::index', compact('logs', 'isAdmin'));
    }

    /**
     * Show check-in form (simple for now).
     */
    public function create()
    {
        return view('attendance::create');
    }

    /**
     * Store a new check-in.
     */
    public function store(Request $request)
    {
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
        $user = auth()->user();
        $isAdmin = $user->hasRole(['tadmin', 'tmanager', 'superadmin']);
        
        $log = AttendanceLog::with('employee')->findOrFail($id);

        // Security check: regular employees can only see their own logs
        if (!$isAdmin && $log->employee_id !== $user->employee?->id) {
            abort(403, 'Unauthorized access to attendance log.');
        }

        return view('attendance::show', compact('log', 'isAdmin'));
    }
}
