<?php

namespace App\Modules\Payroll\Controllers;

use App\Core\BaseController;
use App\Modules\HR\Models\Employee;
use App\Modules\Payroll\Models\SalaryComponent;
use App\Modules\Payroll\Models\SalaryStructure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryStructureController extends BaseController
{
    public function index()
    {
        $structures = SalaryStructure::with('employee')->get();
        return view('modules.payroll.salary_structures.index', compact('structures'));
    }

    public function create(Request $request)
    {
        $employeeId = $request->query('employee_id');
        $employees = Employee::active()->get();
        $components = SalaryComponent::where('is_active', true)->orderBy('display_order')->get();

        return view('modules.payroll.salary_structures.create', compact('employees', 'components', 'employeeId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'ctc' => ['required', 'numeric', 'min:0'],
            'effective_from' => ['required', 'date'],
            'earnings' => ['required', 'array'],
            'deductions' => ['nullable', 'array'],
        ]);

        $grossSalary = array_sum($validated['earnings']);
        $totalDeductions = array_sum($validated['deductions'] ?? []);
        $netSalary = $grossSalary - $totalDeductions;

        // Deactivate existing structures for this employee
        SalaryStructure::where('employee_id', $validated['employee_id'])
            ->where('is_active', true)
            ->update(['is_active' => false, 'effective_to' => Carbon::parse($validated['effective_from'])->subDay()]);

        SalaryStructure::create([
            'tenant_id' => tenant('id'),
            'employee_id' => $validated['employee_id'],
            'ctc' => $validated['ctc'],
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'earnings' => $validated['earnings'],
            'deductions' => $validated['deductions'] ?? [],
            'effective_from' => $validated['effective_from'],
            'is_active' => true,
        ]);

        return redirect()->route('payroll.salary_structures.index')
            ->with('success', 'Salary structure assigned successfully.');
    }

    public function show(SalaryStructure $salary_structure)
    {
        $salary_structure->load('employee');
        $components = SalaryComponent::all()->keyBy('code');
        return view('modules.payroll.salary_structures.show', compact('salary_structure', 'components'));
    }
}
