<?php

namespace App\Modules\Leave\DTOs;

class CompOffData
{
    public function __construct(
        public readonly string $worked_on_date,
        public readonly float $duration,
        public readonly string $reason,
        public readonly int $tenant_id,
        public readonly ?int $employee_id = null,
        public readonly string $status = 'pending',
    ) {}

    public static function fromArray(array $data, int $tenantId, ?int $employeeId = null): self
    {
        return new self(
            worked_on_date: $data['worked_on_date'],
            duration: (float) $data['duration'],
            reason: $data['reason'],
            tenant_id: $tenantId,
            employee_id: $employeeId,
            status: $data['status'] ?? 'pending',
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'worked_on_date' => $this->worked_on_date,
            'duration' => $this->duration,
            'reason' => $this->reason,
            'tenant_id' => $this->tenant_id,
            'employee_id' => $this->employee_id,
            'status' => $this->status,
        ], fn($value) => !is_null($value));
    }
}
