<?php

namespace App\Modules\Recruitment\DTOs;

class JobPostingData
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $location,
        public readonly string $employment_type,
        public readonly string $status,
        public readonly ?string $salary_range,
        public readonly ?string $closing_date,
        public readonly string $description,
        public readonly ?string $requirements
    ) {}

    public static function fromStoreRequest(array $data): self
    {
        return new self(
            title: $data['title'],
            location: $data['location'] ?? null,
            employment_type: $data['employment_type'],
            status: $data['status'],
            salary_range: $data['salary_range'] ?? null,
            closing_date: $data['closing_date'] ?? null,
            description: $data['description'],
            requirements: $data['requirements'] ?? null
        );
    }

    public static function fromUpdateRequest(array $data): self
    {
        return new self(
            title: $data['title'],
            location: $data['location'] ?? null,
            employment_type: $data['employment_type'],
            status: $data['status'],
            salary_range: $data['salary_range'] ?? null,
            closing_date: $data['closing_date'] ?? null,
            description: $data['description'],
            requirements: $data['requirements'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'location' => $this->location,
            'employment_type' => $this->employment_type,
            'status' => $this->status,
            'salary_range' => $this->salary_range,
            'closing_date' => $this->closing_date,
            'description' => $this->description,
            'requirements' => $this->requirements,
        ];
    }
}
