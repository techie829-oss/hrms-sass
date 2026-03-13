<?php

namespace App\Modules\HR\Controllers;

use App\Core\BaseController;
use App\Modules\HR\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends BaseController
{
    public function __construct(
        protected DepartmentService $departmentService
    ) {}

    public function index()
    {
        $departments = $this->departmentService->all();

        return view('modules.hr.departments', compact('departments'));
    }

    public function store(Request $request)
    {
        $this->departmentService->create($request->validated());

        return redirect()->route('hr.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function update(Request $request, int $id)
    {
        $this->departmentService->update($id, $request->validated());

        return redirect()->route('hr.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->departmentService->delete($id);

        return redirect()->route('hr.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
