<?php

namespace App\Modules\Recruitment\DTOs;

use App\Core\BaseDTO;

class PublicJobApplicationData extends BaseDTO
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly ?string $phone = null,
        public readonly ?string $cover_letter = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            first_name: $data['first_name'],
            last_name: $data['last_name'],
            email: $data['email'],
            phone: $data['phone'] ?? null,
            cover_letter: $data['cover_letter'] ?? null,
        );
    }
}
