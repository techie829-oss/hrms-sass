<?php

namespace App\Modules\Operations\DTOs;

class TimesheetData
{
    public function __construct(
        public readonly ?int $project_id,
        public readonly ?int $task_id,
        public readonly string $date,
        public readonly ?string $start_time,
        public readonly ?string $end_time,
        public readonly float $hours,
        public readonly string $description,
        public readonly ?int $employee_id,
        public readonly int $tenant_id
    ) {}

    public static function fromArray(array $data, int $tenantId, ?int $employeeId): self
    {
        return new self(
            project_id: isset($data['project_id']) ? (int)$data['project_id'] : null,
            task_id: isset($data['task_id']) ? (int)$data['task_id'] : null,
            date: $data['date'],
            start_time: $data['start_time'] ?? null,
            end_time: $data['end_time'] ?? null,
            hours: (float)$data['hours'],
            description: $data['description'],
            employee_id: $employeeId,
            tenant_id: $tenantId
        );
    }
}
