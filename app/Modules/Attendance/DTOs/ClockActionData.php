<?php

namespace App\Modules\Attendance\DTOs;

class ClockActionData
{
    public function __construct(
        public ?string $latitude = null,
        public ?string $longitude = null,
        public $photo = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            latitude: $data['latitude'] ?? null,
            longitude: $data['longitude'] ?? null,
            photo: $data['photo'] ?? null
        );
    }
}
