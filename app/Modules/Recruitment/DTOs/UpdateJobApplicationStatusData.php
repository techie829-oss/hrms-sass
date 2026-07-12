<?php

namespace App\Modules\Recruitment\DTOs;

class UpdateJobApplicationStatusData
{
    public function __construct(
        public readonly string $status,
        public readonly ?string $notes
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            status: $data['status'],
            notes: $data['notes'] ?? null
        );
    }
}
