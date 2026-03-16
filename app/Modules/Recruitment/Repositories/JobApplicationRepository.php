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
}
