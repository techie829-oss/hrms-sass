<?php

namespace App\Modules\Attendance\Controllers;

use App\Core\BaseController;
use App\Modules\Attendance\Services\AttendanceService;
use App\Modules\Attendance\Requests\UpdateShiftAssignmentsRequest;
use App\Modules\Attendance\DTOs\UpdateShiftAssignmentsData;

class ShiftAssignmentController extends BaseController
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    public function index()
    {
        $employees = $this->attendanceService->getEmployeesWithShifts();
        $shifts = $this->attendanceService->getActiveShifts();
        return view('attendance::shifts.assignments', compact('employees', 'shifts'));
    }

    public function update(UpdateShiftAssignmentsRequest $request)
    {
        $dto = UpdateShiftAssignmentsData::fromRequest($request->validated());

        $this->attendanceService->updateShiftAssignments($dto->assignments);

        return redirect()->back()->with('success', 'Shift assignments updated successfully.');
    }
}
