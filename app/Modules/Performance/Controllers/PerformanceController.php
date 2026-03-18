<?php

namespace App\Modules\Performance\Controllers;

use App\Core\BaseController;
use App\Modules\Performance\Models\KPI;
use App\Modules\Performance\Models\Appraisal;
use App\Modules\Performance\Models\Goal;
use Illuminate\Http\Request;

class PerformanceController extends BaseController
{
    public function index()
    {
        $kpiCount = KPI::count();
        $pendingAppraisals = Appraisal::where('status', 'pending')->count();
        $activeGoals = Goal::where('status', 'in_progress')->count();

        $recentAppraisals = Appraisal::with('employee')
            ->latest()
            ->take(5)
            ->get();

        return view('performance::dashboard', compact(
            'kpiCount', 
            'pendingAppraisals', 
            'activeGoals',
            'recentAppraisals'
        ));
    }
}
