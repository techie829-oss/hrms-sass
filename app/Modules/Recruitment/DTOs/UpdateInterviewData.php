<?php

namespace App\Modules\Recruitment\DTOs;

class UpdateInterviewData
{
    public function __construct(
        public readonly string $status,
        public readonly ?string $feedback
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            status: $data['status'],
            feedback: $data['feedback'] ?? null
        );
    }
}
