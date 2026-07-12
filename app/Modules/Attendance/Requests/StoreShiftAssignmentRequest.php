<?php

namespace App\Modules\Attendance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Core\Constants\PermissionConstants;

class StoreShiftAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(PermissionConstants::MANAGE_ATTENDANCE);
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'shift_id'    => ['required', 'exists:attendance_shifts,id'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}
