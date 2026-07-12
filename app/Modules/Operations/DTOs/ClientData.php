<?php

namespace App\Modules\Operations\DTOs;

class ClientData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?string $company,
        public readonly ?string $address,
        public readonly int $tenant_id
    ) {}

    public static function fromArray(array $data, int $tenantId): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            company: $data['company'] ?? null,
            address: $data['address'] ?? null,
            tenant_id: $tenantId
        );
    }
}
