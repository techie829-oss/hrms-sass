<?php

namespace App\Modules\HR\Controllers;

use App\Core\BaseController;
use App\Modules\HR\Services\DepartmentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Core\Constants\PermissionConstants;
use App\Modules\HR\Requests\StoreDepartmentRequest;
use App\Modules\HR\Requests\UpdateDepartmentRequest;
use App\Modules\HR\DTOs\DepartmentData;

class DepartmentController extends BaseController
{
    public function __construct(
        protected DepartmentService $departmentService
    ) {}

    public function index()
    {
        $this->authorize(PermissionConstants::VIEW_DEPARTMENTS);

        $departments = $this->departmentService->getAllWithCounts();

        return view('hr::departments.index', compact('departments'));
    }

    public function show(int $id)
    {
        $this->authorize(PermissionConstants::VIEW_DEPARTMENTS);

        $department = $this->departmentService->findWithEmployees($id);

        return view('hr::departments.show', compact('department'));
    }

    public function store(StoreDepartmentRequest $request)
    {
        $dto = DepartmentData::fromRequest($request->validated());

        $this->departmentService->create($dto->toArray());

        return redirect()->route('hr.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function update(UpdateDepartmentRequest $request, int $id)
    {
        $dto = DepartmentData::fromRequest($request->validated());

        $this->departmentService->update($id, $dto->toArray());

        return redirect()->route('hr.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->authorize(PermissionConstants::MANAGE_DEPARTMENTS);

        $this->departmentService->delete($id);

        return redirect()->route('hr.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
