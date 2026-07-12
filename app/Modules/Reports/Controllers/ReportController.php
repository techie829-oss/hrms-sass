<?php

namespace App\Modules\Reports\Controllers;

use App\Core\BaseController;
use Illuminate\Http\Request;
use App\Modules\Reports\Services\ReportService;
use App\Core\Constants\PermissionConstants;

class ReportController extends BaseController
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    public function index()
    {
        $this->authorize(PermissionConstants::VIEW_REPORTS);
        return view('reports::index');
    }

    public function workforce()
    {
        $this->authorize(PermissionConstants::VIEW_REPORTS);
        $data = $this->reportService->getWorkforceReport();
        return view('reports::workforce', $data);
    }

    public function attendance(Request $request)
    {
        $this->authorize(PermissionConstants::VIEW_REPORTS);
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $data = $this->reportService->getAttendanceReport((int)$month, (int)$year);
        return view('reports::attendance', $data);
    }

    public function payroll(Request $request)
    {
        $this->authorize(PermissionConstants::VIEW_REPORTS);
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $data = $this->reportService->getPayrollReport((int)$month, (int)$year);
        return view('reports::payroll', $data);
    }
}
