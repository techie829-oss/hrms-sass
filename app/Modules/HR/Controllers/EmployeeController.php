<?php

namespace App\Modules\HR\Controllers;

use App\Core\BaseController;
use App\Modules\HR\Models\Department;
use App\Modules\HR\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends BaseController
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {}

    /**
     * Display employees listing.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['department_id', 'status', 'employment_type']);
        $employees = $this->employeeService->all($filters);

        return view('hr::employees.index', compact('employees'));
    }

    /**
     * Show create employee form.
     */
    public function create(Request $request)
    {
        $departments = Department::all();
        return view('hr::employees.create', compact('departments'))->with($request->all());
    }

    /**
     * Store a new employee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'max:20', Rule::unique('employees')->where('tenant_id', saas_tenant('id'))],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('employees')->where('tenant_id', saas_tenant('id'))],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'date_of_joining' => ['required', 'date'],
            'employment_type' => ['required', Rule::in(['full_time', 'part_time', 'contract', 'intern'])],
            'status' => ['required', Rule::in(['active', 'inactive', 'on_leave', 'terminated', 'resigned'])],
            'basic_salary' => ['required', 'numeric', 'min:0'],
        ]);

        $this->employeeService->create($validated);

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Show employee details.
     */
    public function show($id)
    {
        $employee = $this->employeeService->findOrFail($id);
        
        // Eager load relationships if needed, or fetch related data
        $employee->load(['department', 'appraisals', 'goals']);

        return view('hr::employees.show', compact('employee'));
    }

    /**
     * Show edit employee form.
     */
    public function edit($id)
    {
        $employee = $this->employeeService->findOrFail($id);
        $departments = Department::all();

        return view('hr::employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update an employee.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'max:20', Rule::unique('employees')->where('tenant_id', saas_tenant('id'))->ignore($id)],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('employees')->where('tenant_id', saas_tenant('id'))->ignore($id)],
            'department_id' => ['nullable', 'exists:departments,id'],
            'date_of_joining' => ['required', 'date'],
            'employment_type' => ['required', Rule::in(['full_time', 'part_time', 'contract', 'intern'])],
            'status' => ['required', Rule::in(['active', 'inactive', 'on_leave', 'terminated', 'resigned'])],
            'basic_salary' => ['required', 'numeric', 'min:0'],
        ]);

        $this->employeeService->update($id, $validated);

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Delete an employee.
     */
    public function destroy($id)
    {
        $this->employeeService->delete($id);

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
