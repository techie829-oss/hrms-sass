<?php

namespace App\Modules\Attendance\DTOs;

class ShiftAssignmentData
{
    public function __construct(
        public int $employee_id,
        public int $shift_id,
        public string $start_date,
        public ?string $end_date = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            employee_id: $data['employee_id'],
            shift_id: $data['shift_id'],
            start_date: $data['start_date'],
            end_date: $data['end_date'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'employee_id' => $this->employee_id,
            'shift_id' => $this->shift_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
