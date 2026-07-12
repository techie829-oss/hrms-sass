<?php

namespace App\Modules\Leave\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompOffStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Handled by controller policy
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:approved,rejected'],
        ];
    }
}
