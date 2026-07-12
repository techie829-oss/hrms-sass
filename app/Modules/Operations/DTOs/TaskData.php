<?php

namespace App\Modules\Operations\DTOs;

class TaskData
{
    public function __construct(
        public readonly ?string $title,
        public readonly ?int $project_id,
        public readonly ?int $assigned_to,
        public readonly ?string $status,
        public readonly ?string $priority,
        public readonly ?string $due_date,
        public readonly ?string $description,
        public readonly int $tenant_id,
        public readonly ?string $completed_at = null,
    ) {}

    public static function fromArray(array $data, int $tenantId): self
    {
        $status = $data['status'] ?? 'todo';
        $completedAt = null;

        if ($status === 'done') {
            $completedAt = now()->toDateTimeString();
        }

        return new self(
            title: $data['title'] ?? null,
            project_id: $data['project_id'] ?? null,
            assigned_to: $data['assigned_to'] ?? null,
            status: $status,
            priority: $data['priority'] ?? null,
            due_date: $data['due_date'] ?? null,
            description: $data['description'] ?? null,
            tenant_id: $tenantId,
            completed_at: $completedAt,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'project_id' => $this->project_id,
            'assigned_to' => $this->assigned_to,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
            'description' => $this->description,
            'tenant_id' => $this->tenant_id,
            'completed_at' => $this->completed_at,
        ], fn($value) => !is_null($value));
    }
}
