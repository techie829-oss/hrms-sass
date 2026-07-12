<?php

namespace App\Modules\Leave\DTOs;

class HolidayData
{
    public function __construct(
        public readonly string $name,
        public readonly string $date,
        public readonly bool $is_optional,
        public readonly ?string $description,
        public readonly int $tenant_id
    ) {}

    public static function fromArray(array $data, int $tenantId): self
    {
        return new self(
            name: $data['name'],
            date: $data['date'],
            is_optional: $data['is_optional'] ?? false,
            description: $data['description'] ?? null,
            tenant_id: $tenantId
        );
    }
}
