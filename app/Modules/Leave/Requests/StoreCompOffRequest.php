<?php

namespace App\Modules\Leave\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompOffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Handled by controller policy
    }

    public function rules(): array
    {
        return [
            'worked_on_date' => ['required', 'date'],
            'duration' => ['required', 'numeric', 'in:0.5,1.0'],
            'reason' => ['required', 'string', 'max:500'],
        ];
    }
}
