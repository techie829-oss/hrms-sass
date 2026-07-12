<?php

namespace App\Modules\Performance\Controllers;

use App\Core\BaseController;
use App\Modules\Performance\Models\Appraisal;
use App\Modules\HR\Services\EmployeeService;
use Illuminate\Support\Facades\Auth;
use App\Modules\Performance\Requests\StoreAppraisalRequest;
use App\Modules\Performance\Requests\UpdateAppraisalRequest;
use App\Modules\Performance\DTOs\AppraisalData;
use App\Modules\Performance\Services\AppraisalService;

class AppraisalController extends BaseController
{
    public function __construct(
        protected AppraisalService $appraisalService,
        protected EmployeeService $employeeService
    ) {
        $this->authorizeResource(Appraisal::class, 'appraisal');
    }

    public function index()
    {
        $appraisals = $this->appraisalService->getPaginatedWithRelations(15);
        $employees = $this->employeeService->getActiveEmployees();
        return view('performance::appraisals.index', compact('appraisals', 'employees'));
    }

    public function store(StoreAppraisalRequest $request)
    {
        $evaluatorId = Auth::user()->employee->id ?? Auth::id(); // Fallback to user ID if no employee record
        
        $dto = AppraisalData::fromStoreRequest($request->validated(), $evaluatorId);
        $this->appraisalService->createAppraisal($dto);

        return redirect()->route('performance.appraisals.index')
            ->with('success', 'Appraisal initiated successfully.');
    }

    public function update(UpdateAppraisalRequest $request, Appraisal $appraisal)
    {
        $dto = AppraisalData::fromUpdateRequest(
            $request->validated(), 
            $appraisal->employee_id, 
            $appraisal->evaluator_id, 
            $appraisal->review_period
        );
        $this->appraisalService->updateAppraisal($appraisal, $dto);

        return redirect()->route('performance.appraisals.index')
            ->with('success', 'Appraisal updated successfully.');
    }
}
