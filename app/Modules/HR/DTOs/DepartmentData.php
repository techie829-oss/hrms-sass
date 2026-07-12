<?php

namespace App\Modules\HR\DTOs;

class DepartmentData
{
    public function __construct(
        public string $name,
        public string $code,
        public ?string $description = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            code: $data['code'],
            description: $data['description'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
        ];
    }
}
