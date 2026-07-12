<?php

namespace App\Modules\Operations\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimesheetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorized via controller
    }

    public function rules(): array
    {
        return [
            'project_id' => 'nullable|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'hours' => 'required|numeric|min:0.01|max:24',
            'description' => 'required|string',
        ];
    }
}
