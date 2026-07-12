<?php

namespace App\Modules\Performance\DTOs;

class KPIData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly ?int $department_id,
        public readonly float $target_value,
        public readonly string $unit
    ) {}

    public static function fromStoreRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            department_id: isset($data['department_id']) ? (int)$data['department_id'] : null,
            target_value: (float)$data['target_value'],
            unit: $data['unit']
        );
    }
    
    public function toStoreArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'department_id' => $this->department_id,
            'target_value' => $this->target_value,
            'unit' => $this->unit,
        ];
    }
}
