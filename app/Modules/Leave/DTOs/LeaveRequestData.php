<?php

namespace App\Modules\Leave\DTOs;

class LeaveRequestData
{
    public function __construct(
        public readonly int $employee_id,
        public readonly int $leave_type_id,
        public readonly \DateTimeImmutable $start_date,
        public readonly \DateTimeImmutable $end_date,
        public readonly bool $is_half_day,
        public readonly ?string $half_day_type,
        public readonly string $reason
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            employee_id: (int) $data['employee_id'],
            leave_type_id: (int) $data['leave_type_id'],
            start_date: new \DateTimeImmutable($data['start_date']),
            end_date: new \DateTimeImmutable($data['end_date']),
            is_half_day: (bool) ($data['is_half_day'] ?? false),
            half_day_type: $data['half_day_type'] ?? null,
            reason: (string) $data['reason']
        );
    }
}
