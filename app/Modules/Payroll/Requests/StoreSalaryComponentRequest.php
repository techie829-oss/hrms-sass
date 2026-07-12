<?php

namespace App\Modules\Payroll\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Payroll\Models\PayrollRun;

class StoreSalaryComponentRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('manage', PayrollRun::class);
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20'],
            'type' => ['required', 'in:earning,deduction'],
            'calculation_type' => ['required', 'in:fixed,percentage'],
            'default_amount' => ['required', 'numeric', 'min:0'],
            'percentage_base' => ['nullable', 'string', 'max:20'],
            'is_taxable' => ['boolean'],
            'is_mandatory' => ['boolean'],
            'display_order' => ['integer'],
        ];
    }
}
