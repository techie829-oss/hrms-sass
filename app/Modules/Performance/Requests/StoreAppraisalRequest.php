<?php

namespace App\Modules\Performance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppraisalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'review_period' => ['required', 'string', 'max:255'],
            'comments' => ['nullable', 'string'],
        ];
    }
}
