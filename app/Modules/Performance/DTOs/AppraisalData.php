<?php

namespace App\Modules\Performance\DTOs;

class AppraisalData
{
    public function __construct(
        public readonly int $employee_id,
        public readonly int $evaluator_id,
        public readonly string $review_period,
        public readonly ?float $score,
        public readonly ?string $comments,
        public readonly string $status
    ) {}

    public static function fromStoreRequest(array $data, int $evaluatorId): self
    {
        return new self(
            employee_id: (int)$data['employee_id'],
            evaluator_id: $evaluatorId,
            review_period: $data['review_period'],
            score: null,
            comments: $data['comments'] ?? null,
            status: 'pending'
        );
    }

    public static function fromUpdateRequest(array $data, int $employeeId, int $evaluatorId, string $reviewPeriod): self
    {
        return new self(
            employee_id: $employeeId,
            evaluator_id: $evaluatorId,
            review_period: $reviewPeriod,
            score: isset($data['score']) ? (float)$data['score'] : null,
            comments: $data['comments'] ?? null,
            status: $data['status']
        );
    }
    
    public function toStoreArray(): array
    {
        return [
            'employee_id' => $this->employee_id,
            'evaluator_id' => $this->evaluator_id,
            'review_period' => $this->review_period,
            'comments' => $this->comments,
            'status' => $this->status,
        ];
    }
    
    public function toUpdateArray(): array
    {
        return [
            'score' => $this->score,
            'comments' => $this->comments,
            'status' => $this->status,
        ];
    }
}
