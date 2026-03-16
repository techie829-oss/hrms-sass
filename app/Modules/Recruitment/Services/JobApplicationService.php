<?php

namespace App\Modules\Recruitment\Services;

use App\Core\BaseService;
use App\Modules\Recruitment\Interfaces\JobApplicationRepositoryInterface;

class JobApplicationService extends BaseService
{
    public function __construct(JobApplicationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getByPosting(int $postingId)
    {
        return $this->repository->getByPosting($postingId);
    }
}
