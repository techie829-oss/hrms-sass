<?php

namespace App\Modules\Performance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppraisalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'comments' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,pending,completed'],
        ];
    }
}
