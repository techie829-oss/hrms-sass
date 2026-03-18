<?php

namespace App\Modules\Attendance\Controllers;

use App\Core\BaseController;
use App\Modules\HR\Models\Employee;
use App\Modules\Attendance\Models\AttendanceShift;
use Illuminate\Http\Request;

class ShiftAssignmentController extends BaseController
{
    public function index()
    {
        $employees = Employee::with('attendanceShift')->active()->get();
        $shifts = AttendanceShift::where('is_active', true)->get();
        return view('attendance::shifts.assignments', compact('employees', 'shifts'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'assignments' => ['required', 'array'],
            'assignments.*.employee_id' => ['required', 'exists:employees,id'],
            'assignments.*.shift_id' => ['nullable', 'exists:attendance_shifts,id'],
        ]);

        foreach ($validated['assignments'] as $assignment) {
            Employee::where('id', $assignment['employee_id'])->update([
                'attendance_shift_id' => $assignment['shift_id'],
            ]);
        }

        return redirect()->back()->with('success', 'Shift assignments updated successfully.');
    }
}
