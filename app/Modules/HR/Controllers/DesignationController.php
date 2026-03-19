<?php

namespace App\Modules\HR\Controllers;

use App\Core\BaseController;
use App\Modules\HR\Models\Department;
use App\Modules\HR\Services\DesignationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DesignationController extends BaseController
{
    public function __construct(
        protected DesignationService $designationService
    ) {}

    public function index()
    {
        $designations = $this->designationService->getAllWithCounts();
        $departments = Department::all();
        
        return view('hr::designations.index', compact('designations', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('designations')->where('tenant_id', tenant('id'))],
            'department_id' => ['required', 'exists:departments,id'],
            'description' => ['nullable', 'string'],
            'min_salary' => ['nullable', 'numeric', 'min:0'],
            'max_salary' => ['nullable', 'numeric', 'min:0', 'gte:min_salary'],
            'is_active' => ['boolean']
        ]);

        $this->designationService->create($validated);

        return redirect()->route('hr.designations.index')
            ->with('success', 'Designation created successfully.');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('designations')->where('tenant_id', tenant('id'))->ignore($id)],
            'department_id' => ['required', 'exists:departments,id'],
            'description' => ['nullable', 'string'],
            'min_salary' => ['nullable', 'numeric', 'min:0'],
            'max_salary' => ['nullable', 'numeric', 'min:0', 'gte:min_salary'],
            'is_active' => ['boolean']
        ]);

        $this->designationService->update($id, $validated);

        return redirect()->route('hr.designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->designationService->delete($id);

        return redirect()->route('hr.designations.index')
            ->with('success', 'Designation deleted successfully.');
    }
}
