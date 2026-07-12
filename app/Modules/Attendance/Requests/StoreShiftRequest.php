<?php

namespace App\Modules\Attendance\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Modules\Attendance\Models\AttendanceLog;

class StoreShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage', AttendanceLog::class);
    }

    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:100'],
            'start_time'          => ['required', 'date_format:H:i'],
            'end_time'            => ['required', 'date_format:H:i'],
            'grace_minutes'       => ['required', 'integer', 'min:0', 'max:120'],
            'half_day_hours'      => ['required', 'integer', 'min:1', 'max:12'],
            'min_hours_full_day'  => ['nullable', 'integer', 'min:1', 'max:24'],
            'weekly_offs'         => ['nullable', 'array'],
            'weekly_offs.*'       => ['string', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'is_default'          => ['nullable', 'boolean'],
            'is_overnight'        => ['nullable', 'boolean'],
        ];
    }
}
