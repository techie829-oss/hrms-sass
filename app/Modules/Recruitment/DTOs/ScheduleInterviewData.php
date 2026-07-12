<?php

namespace App\Modules\Recruitment\DTOs;

class ScheduleInterviewData
{
    public function __construct(
        public readonly string $interview_date,
        public readonly string $type,
        public readonly ?string $location,
        public readonly ?int $interviewer_id
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            interview_date: $data['interview_date'],
            type: $data['type'],
            location: $data['location'] ?? null,
            interviewer_id: isset($data['interviewer_id']) ? (int)$data['interviewer_id'] : null
        );
    }

    public function toArray(): array
    {
        return [
            'interview_date' => $this->interview_date,
            'type' => $this->type,
            'location' => $this->location,
            'interviewer_id' => $this->interviewer_id,
        ];
    }
}
