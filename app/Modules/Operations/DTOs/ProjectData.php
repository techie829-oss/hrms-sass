<?php

namespace App\Modules\Operations\DTOs;

class ProjectData
{
    public function __construct(
        public readonly string $name,
        public readonly ?int $client_id,
        public readonly ?string $description,
        public readonly ?string $start_date,
        public readonly ?string $deadline,
        public readonly ?float $budget,
        public readonly int $tenant_id,
    ) {}

    public static function fromArray(array $data, int $tenantId): self
    {
        return new self(
            name: $data['name'],
            client_id: $data['client_id'] ?? null,
            description: $data['description'] ?? null,
            start_date: $data['start_date'] ?? null,
            deadline: $data['deadline'] ?? null,
            budget: isset($data['budget']) ? (float)$data['budget'] : null,
            tenant_id: $tenantId,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'client_id' => $this->client_id,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'deadline' => $this->deadline,
            'budget' => $this->budget,
            'tenant_id' => $this->tenant_id,
        ];
    }
}
