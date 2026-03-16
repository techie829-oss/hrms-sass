<?php

namespace App\Modules\Recruitment\Repositories;

use App\Core\BaseRepository;
use App\Modules\Recruitment\Interfaces\JobPostingRepositoryInterface;
use App\Modules\Recruitment\Models\JobPosting;

class JobPostingRepository extends BaseRepository implements JobPostingRepositoryInterface
{
    public function __construct(JobPosting $model)
    {
        $this->model = $model;
    }

    public function getActivePostings()
    {
        return $this->model->where('status', 'open')->latest()->get();
    }
}
