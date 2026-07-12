<?php

namespace App\Modules\Leave\DTOs;

class LeaveRequestBulkData
{
    public function __construct(
        public readonly array $employee_ids,
        public readonly int $leave_type_id,
        public readonly \DateTimeImmutable $start_date,
        public readonly \DateTimeImmutable $end_date,
        public readonly string $reason
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            employee_ids: array_map('intval', (array) $data['employee_ids']),
            leave_type_id: (int) $data['leave_type_id'],
            start_date: new \DateTimeImmutable($data['start_date']),
            end_date: new \DateTimeImmutable($data['end_date']),
            reason: (string) $data['reason']
        );
    }
}
