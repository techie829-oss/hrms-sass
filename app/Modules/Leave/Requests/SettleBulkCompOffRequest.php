<?php

namespace App\Modules\Leave\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettleBulkCompOffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Handled by controller policy
    }

    public function rules(): array
    {
        return [
            'reference_date' => ['required', 'date'],
            'target_date' => ['required', 'date'],
        ];
    }
}
