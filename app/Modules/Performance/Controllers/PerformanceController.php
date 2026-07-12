<?php

namespace App\Modules\Performance\Controllers;

use App\Core\BaseController;
use App\Modules\Performance\Models\Appraisal;
use App\Modules\Performance\Services\KPIService;
use App\Modules\Performance\Services\AppraisalService;
use App\Modules\Performance\Services\GoalService;

class PerformanceController extends BaseController
{
    public function __construct(
        protected KPIService $kpiService,
        protected AppraisalService $appraisalService,
        protected GoalService $goalService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Appraisal::class);
        $kpiCount = $this->kpiService->getCount();
        $pendingAppraisals = $this->appraisalService->getPendingCount();
        $activeGoals = $this->goalService->getActiveCount();
        $recentAppraisals = $this->appraisalService->getRecentAppraisals(5);

        return view('performance::dashboard', compact(
            'kpiCount', 
            'pendingAppraisals', 
            'activeGoals',
            'recentAppraisals'
        ));
    }
}
