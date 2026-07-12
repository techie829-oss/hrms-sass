<?php

namespace App\Modules\Performance\Controllers;

use App\Core\BaseController;
use App\Modules\Performance\Models\KPI;
use App\Modules\HR\Services\DepartmentService;
use App\Modules\Performance\Requests\StoreKPIRequest;
use App\Modules\Performance\DTOs\KPIData;
use App\Modules\Performance\Services\KPIService;

class KPIController extends BaseController
{
    public function __construct(
        protected KPIService $kpiService,
        protected DepartmentService $departmentService
    ) {
        $this->authorizeResource(KPI::class, 'kpi');
    }

    public function index()
    {
        $kpis = $this->kpiService->getPaginatedWithRelations(15);
        $departments = $this->departmentService->getAllWithCounts();
        return view('performance::kpis.index', compact('kpis', 'departments'));
    }

    public function store(StoreKPIRequest $request)
    {
        $dto = KPIData::fromStoreRequest($request->validated());
        $this->kpiService->createKPI($dto);

        return redirect()->route('performance.kpis.index')
            ->with('success', 'KPI created successfully.');
    }
}
