<?php

namespace App\Modules\Payroll\Controllers;

use App\Core\BaseController;
use App\Modules\Payroll\Models\PayrollRun;
use App\Modules\Payroll\Models\Payslip;
use App\Modules\Payroll\Models\SalaryComponent;
use App\Modules\Payroll\Models\SalaryStructure;
use App\Modules\HR\Models\Employee;
use App\Modules\Payroll\Services\PayrollService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayrollController extends BaseController
{
    public function __construct(
        protected PayrollService $payrollService
    ) {}

    public function index()
    {
        $runs = PayrollRun::latest()->paginate(10);
        return view('modules.payroll.index', compact('runs'));
    }

    public function create()
    {
        return view('modules.payroll.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:2020'],
            'pay_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $title = \Carbon\Carbon::createFromDate($validated['year'], $validated['month'], 1)->format('F Y') . " Payroll";

        $run = PayrollRun::create(array_merge($validated, [
            'title' => $title,
            'status' => 'draft',
        ]));

        return redirect()->route('payroll.show', $run->id)
            ->with('success', 'Payroll run initialized as draft.');
    }

    public function show(PayrollRun $run)
    {
        $payslips = $run->payslips()->with('employee')->paginate(20);
        return view('modules.payroll.show', compact('run', 'payslips'));
    }

    public function generate(PayrollRun $run)
    {
        if ($run->status === 'completed') {
            return back()->with('error', 'Completed payroll runs cannot be re-generated.');
        }

        try {
            $processed = $this->payrollService->generatePayslips($run);
            return back()->with('success', "Payslips for $processed employees generated successfully.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate payslips: ' . $e->getMessage());
        }
    }

    public function download(Payslip $payslip)
    {
        $payslip->load('employee', 'payrollRun');
        return view('modules.payroll.payslip_print', compact('payslip'));
    }
}
