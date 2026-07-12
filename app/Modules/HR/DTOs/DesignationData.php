<?php

namespace App\Modules\HR\DTOs;

class DesignationData
{
    public function __construct(
        public string $name,
        public string $code,
        public int $department_id,
        public ?string $description = null,
        public ?float $min_salary = null,
        public ?float $max_salary = null,
        public bool $is_active = true
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            code: $data['code'],
            department_id: $data['department_id'],
            description: $data['description'] ?? null,
            min_salary: isset($data['min_salary']) ? (float)$data['min_salary'] : null,
            max_salary: isset($data['max_salary']) ? (float)$data['max_salary'] : null,
            is_active: $data['is_active'] ?? true
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'department_id' => $this->department_id,
            'description' => $this->description,
            'min_salary' => $this->min_salary,
            'max_salary' => $this->max_salary,
            'is_active' => $this->is_active,
        ];
    }
}
