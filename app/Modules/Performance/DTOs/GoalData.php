<?php

namespace App\Modules\Performance\DTOs;

class GoalData
{
    public function __construct(
        public readonly ?int $employee_id,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?string $start_date,
        public readonly ?string $end_date,
        public readonly ?int $progress_percentage,
        public readonly ?string $status
    ) {}

    public static function fromStoreRequest(array $data): self
    {
        return new self(
            employee_id: (int)$data['employee_id'],
            title: $data['title'],
            description: $data['description'] ?? null,
            start_date: $data['start_date'],
            end_date: $data['end_date'],
            progress_percentage: null,
            status: null
        );
    }

    public static function fromUpdateRequest(array $data): self
    {
        return new self(
            employee_id: null,
            title: null,
            description: null,
            start_date: null,
            end_date: null,
            progress_percentage: (int)$data['progress_percentage'],
            status: $data['status']
        );
    }
    
    public function toStoreArray(): array
    {
        return [
            'employee_id' => $this->employee_id,
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
    
    public function toUpdateArray(): array
    {
        return [
            'progress_percentage' => $this->progress_percentage,
            'status' => $this->status,
        ];
    }
}
