<?php

namespace App\Modules\Attendance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Attendance\Models\AttendanceLog;

class StoreAttendanceLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', AttendanceLog::class);
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'date' => ['required', 'date'],
            'check_in' => ['nullable', 'date_format:H:i'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
