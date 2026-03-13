<?php

namespace App\Modules\HR\Controllers;

use App\Core\BaseController;
use App\Modules\HR\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends BaseController
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {}

    /**
     * Display employees listing.
     */
    public function index()
    {
        $employees = $this->employeeService->all();

        return view('modules.hr.employees', compact('employees'));
    }

    /**
     * Show create employee form.
     */
    public function create()
    {
        return view('modules.hr.employee-create');
    }

    /**
     * Store a new employee.
     */
    public function store(Request $request)
    {
        $employee = $this->employeeService->create($request->validated());

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Show employee details.
     */
    public function show(int $id)
    {
        $employee = $this->employeeService->find($id);

        return view('modules.hr.employee-show', compact('employee'));
    }

    /**
     * Update an employee.
     */
    public function update(Request $request, int $id)
    {
        $this->employeeService->update($id, $request->validated());

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Delete an employee.
     */
    public function destroy(int $id)
    {
        $this->employeeService->delete($id);

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
