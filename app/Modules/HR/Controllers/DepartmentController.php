<?php

namespace App\Modules\HR\Controllers;

use App\Core\BaseController;
use App\Modules\HR\Services\DepartmentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends BaseController
{
    public function __construct(
        protected DepartmentService $departmentService
    ) {}

    public function index()
    {
        $departments = $this->departmentService->getAllWithCounts();

        return view('modules.hr.departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments')->where('tenant_id', tenant('id'))],
            'code' => ['required', 'string', 'max:20', Rule::unique('departments')->where('tenant_id', tenant('id'))],
            'description' => ['nullable', 'string'],
        ]);

        $this->departmentService->create($validated);

        return redirect()->route('hr.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments')->where('tenant_id', tenant('id'))->ignore($id)],
            'code' => ['required', 'string', 'max:20', Rule::unique('departments')->where('tenant_id', tenant('id'))->ignore($id)],
            'description' => ['nullable', 'string'],
        ]);

        $this->departmentService->update($id, $validated);

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
