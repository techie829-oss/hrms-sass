<?php

namespace App\Modules\Attendance\DTOs;

class ShiftData
{
    public function __construct(
        public string $name,
        public string $start_time,
        public string $end_time,
        public int $grace_minutes,
        public int $half_day_hours,
        public ?int $min_hours_full_day = null,
        public ?array $weekly_offs = null,
        public bool $is_default = false,
        public bool $is_overnight = false
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            start_time: $data['start_time'],
            end_time: $data['end_time'],
            grace_minutes: $data['grace_minutes'] ?? 15,
            half_day_hours: $data['half_day_hours'] ?? 4,
            min_hours_full_day: $data['min_hours_full_day'] ?? null,
            weekly_offs: $data['weekly_offs'] ?? ['Saturday', 'Sunday'],
            is_default: isset($data['is_default']) ? (bool)$data['is_default'] : false,
            is_overnight: isset($data['is_overnight']) ? (bool)$data['is_overnight'] : false
        );
    }
}
