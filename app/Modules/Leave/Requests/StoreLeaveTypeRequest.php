<?php

namespace App\Modules\Leave\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Core\Constants\PermissionConstants;

class StoreLeaveTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows(PermissionConstants::MANAGE_LEAVE);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10'],
            'max_days_per_year' => ['required', 'integer', 'min:0'],
            'is_paid' => ['boolean'],
            'is_carry_forward' => ['boolean'],
            'applicable_in_probation' => ['boolean'],
            'description' => ['nullable', 'string'],
        ];
    }
}
