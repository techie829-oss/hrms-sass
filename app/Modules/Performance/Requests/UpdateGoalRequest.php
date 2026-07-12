<?php

namespace App\Modules\Performance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'progress_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['required', 'in:in_progress,completed,cancelled'],
        ];
    }
}
