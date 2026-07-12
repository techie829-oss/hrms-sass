<?php

namespace App\Modules\Payroll\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Payroll\Models\PayrollRun;

class StorePayrollRunRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', PayrollRun::class);
    }

    public function rules()
    {
        return [
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:2020'],
            'pay_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
