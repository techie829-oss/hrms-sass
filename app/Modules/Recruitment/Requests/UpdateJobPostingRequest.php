<?php

namespace App\Modules\Recruitment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobPostingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'required|string|in:full_time,part_time,contract,intern',
            'status' => 'required|string|in:draft,open,closed',
            'salary_range' => 'nullable|string|max:255',
            'closing_date' => 'nullable|date',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
        ];
    }
}
