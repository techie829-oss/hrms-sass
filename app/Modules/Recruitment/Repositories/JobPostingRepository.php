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

    public function all(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model->withCount('applications')->latest();

        foreach ($filters as $field => $value) {
            if (! is_null($value)) {
                $query->where($field, $value);
            }
        }

        return $query->get();
    }

    public function getActivePostings()
    {
        return $this->model->withCount('applications')->where('status', 'open')->latest()->get();
    }
}
