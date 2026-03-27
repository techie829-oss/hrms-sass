<?php

namespace App\Modules\Payroll\Controllers;

use App\Core\BaseController;
use App\Modules\Payroll\Models\SalaryComponent;
use Illuminate\Http\Request;

class SalaryComponentController extends BaseController
{
    public function index()
    {
        $components = SalaryComponent::orderBy('display_order')->get();
        return view('payroll::components.index', compact('components'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20'],
            'type' => ['required', 'in:earning,deduction'],
            'calculation_type' => ['required', 'in:fixed,percentage'],
            'default_amount' => ['required', 'numeric', 'min:0'],
            'percentage_base' => ['nullable', 'string', 'max:20'],
            'is_taxable' => ['boolean'],
            'is_mandatory' => ['boolean'],
            'display_order' => ['integer'],
        ]);

        SalaryComponent::create(array_merge($validated, [
            'tenant_id' => saas_tenant('id'),
            'is_taxable' => $request->has('is_taxable'),
            'is_mandatory' => $request->has('is_mandatory'),
        ]));

        return redirect()->route('payroll.components.index')
            ->with('success', 'Salary component created successfully.');
    }
}
