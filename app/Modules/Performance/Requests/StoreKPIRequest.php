<?php

namespace App\Modules\Performance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKPIRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'target_value' => ['required', 'numeric'],
            'unit' => ['required', 'string', 'max:50'],
        ];
    }
}
