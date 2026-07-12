<?php

namespace App\Modules\HR\Controllers;

use App\Core\BaseController;
use App\Modules\HR\Models\Department;
use App\Modules\HR\Services\DesignationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Core\Constants\PermissionConstants;
use App\Modules\HR\Requests\StoreDesignationRequest;
use App\Modules\HR\Requests\UpdateDesignationRequest;
use App\Modules\HR\DTOs\DesignationData;

class DesignationController extends BaseController
{
    public function __construct(
        protected DesignationService $designationService
    ) {}

    public function index()
    {
        $this->authorize(PermissionConstants::VIEW_DEPARTMENTS);

        $designations = $this->designationService->getAllWithCounts();
        $departments = Department::all();

        return view('hr::designations.index', compact('designations', 'departments'));
    }

    public function store(StoreDesignationRequest $request)
    {
        $dto = DesignationData::fromRequest($request->validated());

        $this->designationService->create($dto->toArray());

        return redirect()->route('hr.designations.index')
            ->with('success', 'Designation created successfully.');
    }

    public function update(UpdateDesignationRequest $request, int $id)
    {
        $dto = DesignationData::fromRequest($request->validated());

        $this->designationService->update($id, $dto->toArray());

        return redirect()->route('hr.designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->authorize(PermissionConstants::MANAGE_DEPARTMENTS);

        $this->designationService->delete($id);

        return redirect()->route('hr.designations.index')
            ->with('success', 'Designation deleted successfully.');
    }
}
