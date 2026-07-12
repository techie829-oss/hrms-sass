<?php

namespace App\Modules\Attendance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Attendance\Models\AttendancePolicy;

class ClockActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Assume controller handles auth
    }

    public function rules(): array
    {
        // For now, return the default policy for the tenant
        $policy = AttendancePolicy::where('is_active', true)->first();

        return [
            'latitude' => ($policy && $policy->kiosk_require_location) ? 'required' : 'nullable',
            'longitude' => ($policy && $policy->kiosk_require_location) ? 'required' : 'nullable',
            'photo' => ($policy && $policy->kiosk_require_photo) ? 'required' : 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required' => 'Location access is mandatory.',
            'longitude.required' => 'Location access is mandatory.',
            'photo.required' => 'Photo capture is mandatory.',
        ];
    }
}
