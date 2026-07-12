<?php

namespace App\Modules\Attendance\DTOs;

class UpdateShiftAssignmentsData
{
    public function __construct(
        public array $assignments
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            assignments: $data['assignments']
        );
    }

    public function toArray(): array
    {
        return [
            'assignments' => $this->assignments
        ];
    }
}
