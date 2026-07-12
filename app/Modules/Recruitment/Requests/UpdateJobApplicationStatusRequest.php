<?php

namespace App\Modules\Recruitment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:new,reviewing,shortlisted,interview,offered,hired,rejected',
            'notes'  => 'nullable|string|max:1000',
        ];
    }
}
