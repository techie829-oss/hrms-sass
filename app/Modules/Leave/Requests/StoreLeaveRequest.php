<?php

namespace App\Modules\Leave\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization is handled by the Controller via Policy
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_half_day' => ['boolean'],
            'half_day_type' => ['nullable', 'required_if:is_half_day,1', 'in:first_half,second_half'],
            'reason' => ['required', 'string', 'max:500'],
        ];
    }
}
