<?php

namespace App\Modules\Recruitment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'interview_date' => 'required|date|after:now',
            'type'           => 'required|in:phone,video,in_person,technical',
            'location'       => 'nullable|string|max:255',
            'interviewer_id' => 'nullable|exists:employees,id',
        ];
    }
}
