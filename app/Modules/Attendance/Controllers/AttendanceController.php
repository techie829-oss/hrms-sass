<?php

namespace App\Modules\Attendance\Controllers;

use App\Core\BaseController;
use App\Modules\Attendance\Services\AttendanceService;
use Illuminate\Http\Request;

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
        $filters = $request->only(['employee_id', 'date', 'status']);
        $logs = $this->attendanceService->paginate(15, $filters);

        return view('modules.attendance.index', compact('logs'));
    }

    /**
     * Show check-in form (simple for now).
     */
    public function create()
    {
        return view('modules.attendance.create');
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
        $log = \App\Modules\Attendance\Models\AttendanceLog::with('employee')->findOrFail($id);

        return view('modules.attendance.show', compact('log'));
    }
}
