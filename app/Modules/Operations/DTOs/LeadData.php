<?php

namespace App\Modules\Operations\DTOs;

class LeadData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?string $company_name,
        public readonly ?string $source,
        public readonly string $status,
        public readonly ?int $assigned_to,
        public readonly ?string $description,
        public readonly int $tenant_id
    ) {}

    public static function fromArray(array $data, int $tenantId): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            company_name: $data['company_name'] ?? null,
            source: $data['source'] ?? null,
            status: $data['status'],
            assigned_to: isset($data['assigned_to']) ? (int)$data['assigned_to'] : null,
            description: $data['description'] ?? null,
            tenant_id: $tenantId
        );
    }
}
