<?php

namespace App\Modules\Operations\DTOs;

class ContactData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?string $job_title,
        public readonly ?array $client_ids,
        public readonly int $tenant_id
    ) {}

    public static function fromArray(array $data, int $tenantId): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            job_title: $data['job_title'] ?? null,
            client_ids: $data['client_ids'] ?? null,
            tenant_id: $tenantId
        );
    }
}
