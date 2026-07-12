<?php

namespace App\Modules\Leave\DTOs;

class LeaveTypeData
{
    public function __construct(
        public readonly string $name,
        public readonly string $code,
        public readonly int $max_days_per_year,
        public readonly bool $is_paid,
        public readonly bool $is_carry_forward,
        public readonly bool $applicable_in_probation,
        public readonly ?string $description,
        public readonly int $tenant_id,
    ) {}

    public static function fromArray(array $data, int $tenantId): self
    {
        return new self(
            name: $data['name'],
            code: $data['code'],
            max_days_per_year: (int) $data['max_days_per_year'],
            is_paid: (bool) ($data['is_paid'] ?? false),
            is_carry_forward: (bool) ($data['is_carry_forward'] ?? false),
            applicable_in_probation: (bool) ($data['applicable_in_probation'] ?? false),
            description: $data['description'] ?? null,
            tenant_id: $tenantId,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'max_days_per_year' => $this->max_days_per_year,
            'is_paid' => $this->is_paid,
            'is_carry_forward' => $this->is_carry_forward,
            'applicable_in_probation' => $this->applicable_in_probation,
            'description' => $this->description,
            'tenant_id' => $this->tenant_id,
        ];
    }
}
