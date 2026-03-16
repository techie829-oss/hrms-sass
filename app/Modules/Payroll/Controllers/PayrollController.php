<?php

namespace App\Modules\Payroll\Controllers;

use App\Core\BaseController;
use App\Modules\Payroll\Models\PayrollRun;
use App\Modules\Payroll\Models\Payslip;
use App\Modules\Payroll\Models\SalaryComponent;
use App\Modules\Payroll\Models\SalaryStructure;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayrollController extends BaseController
{
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

        // This is a simplified generation logic
        $employees = Employee::where('status', 'active')->get();
        
        DB::beginTransaction();
        try {
            foreach ($employees as $employee) {
                $structure = SalaryStructure::where('employee_id', $employee->id)
                    ->where('is_active', true)
                    ->first();
                
                if (!$structure) continue;

                Payslip::updateOrCreate(
                    [
                        'payroll_run_id' => $run->id,
                        'employee_id' => $employee->id,
                        'tenant_id' => tenant('id'),
                    ],
                    [
                        'payslip_number' => 'PS-' . $run->year . $run->month . '-' . $employee->id,
                        'month' => $run->month,
                        'year' => $run->year,
                        'basic_salary' => $structure->gross_salary * 0.5, // Example logic
                        'gross_earnings' => $structure->gross_salary,
                        'total_deductions' => $structure->gross_salary - $structure->net_salary,
                        'net_salary' => $structure->net_salary,
                        'earnings_breakdown' => $structure->earnings,
                        'deductions_breakdown' => $structure->deductions,
                        'status' => 'generated',
                    ]
                );
            }

            $run->update([
                'status' => 'processing',
                'total_employees' => $run->payslips()->count(),
                'total_gross' => $run->payslips()->sum('gross_earnings'),
                'total_deductions' => $run->payslips()->sum('total_deductions'),
                'total_net' => $run->payslips()->sum('net_salary'),
            ]);

            DB::commit();
            return back()->with('success', 'Payslips generated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to generate payslips: ' . $e->getMessage());
        }
    }
}
