<?php

namespace App\Modules\Leave\Controllers;

use App\Core\BaseController;
use App\Modules\Leave\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Core\Constants\PermissionConstants;
use App\Modules\Leave\Requests\StoreLeaveTypeRequest;
use App\Modules\Leave\DTOs\LeaveTypeData;
use App\Modules\Leave\Services\LeaveTypeService;

class LeaveTypeController extends BaseController
{
    public function __construct(
        protected LeaveTypeService $leaveTypeService
    ) {}

    public function index()
    {
        Gate::authorize(PermissionConstants::MANAGE_LEAVE);
        $leaveTypes = $this->leaveTypeService->getActiveLeaveTypes();
        return view('leave::types.index', compact('leaveTypes'));
    }

    public function store(StoreLeaveTypeRequest $request)
    {
        $validated = $request->validated();
        
        $dto = LeaveTypeData::fromArray($validated, saas_tenant('id'));
        $this->leaveTypeService->createLeaveType($dto);

        return redirect()->route('leave.types.index')
            ->with('success', 'Leave type created successfully.');
    }
}
