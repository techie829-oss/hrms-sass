<?php

namespace App\Modules\Recruitment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'   => 'required|in:scheduled,completed,cancelled,no_show',
            'feedback' => 'nullable|string',
        ];
    }
}
