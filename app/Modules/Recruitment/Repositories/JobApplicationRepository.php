<?php

namespace App\Modules\Recruitment\Repositories;

use App\Core\BaseRepository;
use App\Modules\Recruitment\Interfaces\JobApplicationRepositoryInterface;
use App\Modules\Recruitment\Models\JobApplication;

class JobApplicationRepository extends BaseRepository implements JobApplicationRepositoryInterface
{
    public function __construct(JobApplication $model)
    {
        $this->model = $model;
    }

    public function getByPosting(int $postingId)
    {
        return $this->model->where('job_posting_id', $postingId)->latest()->get();
    }

    public function getPaginatedList(array $filters = [], int $perPage = 20)
    {
        return $this->model->with('jobPosting')
            ->when($filters['posting_id'] ?? null, fn($q, $v) => $q->where('job_posting_id', $v))
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->latest('applied_at')
            ->paginate($perPage)
            ->withQueryString();
    }
}
