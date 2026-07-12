<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Payroll\Interfaces\SalaryStructureRepositoryInterface;
use App\Modules\Payroll\Models\SalaryStructure;
use App\Core\BaseRepository;
use Carbon\Carbon;

class SalaryStructureRepository extends BaseRepository implements SalaryStructureRepositoryInterface
{
    public function __construct(SalaryStructure $model)
    {
        $this->model = $model;
    }

    public function getAllWithEmployee()
    {
        return SalaryStructure::with('employee')->get();
    }

    public function deactivateActiveStructuresForEmployee(int $employeeId, string $effectiveToDate)
    {
        return SalaryStructure::where('employee_id', $employeeId)
            ->where('is_active', true)
            ->update([
                'is_active' => false, 
                'effective_to' => Carbon::parse($effectiveToDate)->subDay()
            ]);
    }

    public function createStructure(array $data)
    {
        return SalaryStructure::create($data);
    }
}
