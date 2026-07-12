<?php

namespace App\Modules\Recruitment\Services;

use App\Core\BaseService;
use App\Modules\Recruitment\Interfaces\JobPostingRepositoryInterface;
use App\Modules\Recruitment\DTOs\JobPostingData;

/**
 * @property JobPostingRepositoryInterface $repository
 */
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

    public function createPosting(JobPostingData $data)
    {
        return $this->repository->create($data->toArray());
    }

    public function updatePosting(int|string $id, JobPostingData $data)
    {
        return $this->repository->update($id, $data->toArray());
    }
}
