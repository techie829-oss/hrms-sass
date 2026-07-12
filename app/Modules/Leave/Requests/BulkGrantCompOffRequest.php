<?php

namespace App\Modules\Leave\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkGrantCompOffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Handled by controller policy
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:255'],
        ];
    }
}
