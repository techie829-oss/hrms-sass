<?php

namespace App\Modules\Payroll\Controllers;

use App\Core\BaseController;
use App\Modules\Payroll\Models\PayrollRun;
use App\Modules\Payroll\Models\Payslip;
use App\Modules\Payroll\Services\PayrollService;
use App\Modules\Payroll\Requests\StorePayrollRunRequest;
use App\Modules\Payroll\DTOs\PayrollRunData;

class PayrollController extends BaseController
{
    public function __construct(
        protected PayrollService $payrollService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', PayrollRun::class);
        $runs = $this->payrollService->paginateRuns(10);
        return view('payroll::index', compact('runs'));
    }

    public function create()
    {
        $this->authorize('create', PayrollRun::class);
        return view('payroll::create');
    }

    public function store(StorePayrollRunRequest $request)
    {
        $data = PayrollRunData::fromRequest($request);
        $run = $this->payrollService->createRun($data);

        return redirect()->route('payroll.show', $run->id)
            ->with('success', 'Payroll run initialized as draft.');
    }

    public function show(PayrollRun $run)
    {
        $this->authorize('view', $run);
        $payslips = $this->payrollService->getPayslips($run->id, 20);
        return view('payroll::show', compact('run', 'payslips'));
    }

    public function generate(PayrollRun $run)
    {
        $this->authorize('create', PayrollRun::class);
        if ($run->status === 'completed') {
            return back()->with('error', 'Completed payroll runs cannot be re-generated.');
        }

        try {
            $processed = $this->payrollService->generatePayslips($run->id);
            return back()->with('success', "Payslips for $processed employees generated successfully.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate payslips: ' . $e->getMessage());
        }
    }

    public function download(Payslip $payslip)
    {
        $this->authorize('viewPayslip', [PayrollRun::class, $payslip]);
        $payslip->load('employee', 'payrollRun');
        return view('payroll::payslip_print', compact('payslip'));
    }
}
