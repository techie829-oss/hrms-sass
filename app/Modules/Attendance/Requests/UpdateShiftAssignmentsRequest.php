<?php

namespace App\Modules\Attendance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Attendance\Models\AttendanceLog;

class UpdateShiftAssignmentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage', AttendanceLog::class);
    }

    public function rules(): array
    {
        return [
            'assignments' => ['required', 'array'],
            'assignments.*.employee_id' => ['required', 'exists:employees,id'],
            'assignments.*.shift_id' => ['nullable', 'exists:attendance_shifts,id'],
        ];
    }
}
