<?php

namespace App\Modules\Payroll\Controllers;

use App\Core\BaseController;
use App\Modules\HR\Services\EmployeeService;
use App\Modules\Payroll\Models\SalaryStructure;
use App\Modules\Payroll\Models\PayrollRun;
use App\Modules\Payroll\Services\SalaryStructureService;
use App\Modules\Payroll\Services\SalaryComponentService;
use App\Modules\Payroll\Requests\StoreSalaryStructureRequest;
use App\Modules\Payroll\DTOs\SalaryStructureData;
use Illuminate\Http\Request;

class SalaryStructureController extends BaseController
{
    public function __construct(
        protected SalaryStructureService $salaryStructureService,
        protected SalaryComponentService $salaryComponentService,
        protected EmployeeService $employeeService
    ) {}

    public function index()
    {
        $this->authorize('manage', PayrollRun::class);
        $structures = $this->salaryStructureService->getAllWithEmployee();
        return view('payroll::salary_structures.index', compact('structures'));
    }

    public function create(Request $request)
    {
        $this->authorize('manage', PayrollRun::class);
        $employeeId = $request->query('employee_id');
        $employees = $this->employeeService->getActiveEmployees();
        $components = $this->salaryComponentService->getActiveOrdered();

        return view('payroll::salary_structures.create', compact('employees', 'components', 'employeeId'));
    }

    public function store(StoreSalaryStructureRequest $request)
    {
        $data = SalaryStructureData::fromRequest($request);
        $this->salaryStructureService->createStructure($data);

        return redirect()->route('payroll.salary_structures.index')
            ->with('success', 'Salary structure assigned successfully.');
    }

    public function show(SalaryStructure $salary_structure)
    {
        $this->authorize('manage', PayrollRun::class);
        $salary_structure->load('employee');
        $components = $this->salaryComponentService->getAllKeyedByCode();
        return view('payroll::salary_structures.show', compact('salary_structure', 'components'));
    }
}
