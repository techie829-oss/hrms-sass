<?php

namespace App\Modules\Operations\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'assigned_to' => ['sometimes', 'nullable', 'exists:employees,id'],
            'status' => ['sometimes', 'required', 'in:todo,in_progress,review,done'],
            'priority' => ['sometimes', 'required', 'in:low,medium,high,urgent'],
            'due_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
