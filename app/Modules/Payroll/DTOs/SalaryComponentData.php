<?php

namespace App\Modules\Payroll\DTOs;

use Illuminate\Http\Request;

class SalaryComponentData
{
    public function __construct(
        public string $name,
        public string $code,
        public string $type,
        public string $calculation_type,
        public float $default_amount,
        public ?string $percentage_base,
        public bool $is_taxable,
        public bool $is_mandatory,
        public int $display_order
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->validated('name'),
            $request->validated('code'),
            $request->validated('type'),
            $request->validated('calculation_type'),
            $request->validated('default_amount'),
            $request->validated('percentage_base'),
            $request->has('is_taxable'),
            $request->has('is_mandatory'),
            $request->validated('display_order', 0)
        );
    }
}
