<?php

namespace App\Modules\Payroll\DTOs;

use Illuminate\Http\Request;

class PayrollRunData
{
    public function __construct(
        public int $month,
        public int $year,
        public string $pay_date,
        public ?string $notes
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->validated('month'),
            $request->validated('year'),
            $request->validated('pay_date'),
            $request->validated('notes')
        );
    }
}
