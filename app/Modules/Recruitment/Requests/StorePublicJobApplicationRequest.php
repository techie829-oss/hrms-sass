<?php

namespace App\Modules\Recruitment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicJobApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Public access
    }

    public function rules()
    {
        return [
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:20',
            'cover_letter' => 'nullable|string',
            'resume'       => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ];
    }
}
