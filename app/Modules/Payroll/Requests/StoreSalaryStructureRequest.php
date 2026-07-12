<?php

namespace App\Modules\Payroll\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Payroll\Models\PayrollRun;

class StoreSalaryStructureRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('manage', PayrollRun::class);
    }

    public function rules()
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'ctc' => ['required', 'numeric', 'min:0'],
            'effective_from' => ['required', 'date'],
            'earnings' => ['required', 'array'],
            'deductions' => ['nullable', 'array'],
        ];
    }
}
