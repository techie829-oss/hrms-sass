<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\SaaS\Services\ActivityService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected ActivityService $activityService)
    {
    }

    public function index()
    {
        $recentActivities = $this->activityService->getRecentActivities(5);

        return view('dashboard', compact('recentActivities'));
    }
}
