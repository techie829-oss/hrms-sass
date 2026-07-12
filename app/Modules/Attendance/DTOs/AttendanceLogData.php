<?php

namespace App\Modules\Attendance\DTOs;

class AttendanceLogData
{
    public function __construct(
        public int $employee_id,
        public string $date,
        public ?string $check_in = null,
        public ?string $remarks = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            employee_id: $data['employee_id'],
            date: $data['date'],
            check_in: $data['check_in'] ?? null,
            remarks: $data['remarks'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'employee_id' => $this->employee_id,
            'date' => $this->date,
            'check_in' => $this->check_in,
            'remarks' => $this->remarks,
        ];
    }
}
