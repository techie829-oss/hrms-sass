<?php

namespace App\Modules\Operations\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'status' => 'required|string|in:new,contacted,qualified,lost,converted',
            'assigned_to' => 'nullable|exists:employees,id',
            'description' => 'nullable|string',
        ];
    }
}
