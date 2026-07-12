<?php

namespace App\Modules\Payroll\Controllers;

use App\Core\BaseController;
use App\Modules\Payroll\Services\SalaryComponentService;
use App\Modules\Payroll\Requests\StoreSalaryComponentRequest;
use App\Modules\Payroll\DTOs\SalaryComponentData;
use App\Modules\Payroll\Models\PayrollRun;

class SalaryComponentController extends BaseController
{
    public function __construct(
        protected SalaryComponentService $salaryComponentService
    ) {}

    public function index()
    {
        $this->authorize('manage', PayrollRun::class);
        $components = $this->salaryComponentService->getAllOrdered();
        return view('payroll::components.index', compact('components'));
    }

    public function store(StoreSalaryComponentRequest $request)
    {
        $data = SalaryComponentData::fromRequest($request);
        $this->salaryComponentService->createComponent($data);

        return redirect()->route('payroll.components.index')
            ->with('success', 'Salary component created successfully.');
    }
}
