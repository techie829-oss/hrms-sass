<?php

namespace App\Modules\Recruitment\Services;

use App\Core\BaseService;
use App\Modules\Recruitment\Interfaces\JobPostingRepositoryInterface;

class JobPostingService extends BaseService
{
    public function __construct(JobPostingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getActivePostings()
    {
        return $this->repository->getActivePostings();
    }
}
