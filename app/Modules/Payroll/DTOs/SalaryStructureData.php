<?php

namespace App\Modules\Payroll\DTOs;

use Illuminate\Http\Request;

class SalaryStructureData
{
    public function __construct(
        public int $employee_id,
        public float $ctc,
        public string $effective_from,
        public array $earnings,
        public array $deductions
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->validated('employee_id'),
            $request->validated('ctc'),
            $request->validated('effective_from'),
            $request->validated('earnings'),
            $request->validated('deductions', [])
        );
    }
}
