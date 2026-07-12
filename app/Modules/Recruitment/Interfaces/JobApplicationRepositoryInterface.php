<?php

namespace App\Modules\Recruitment\Interfaces;

use App\Core\BaseRepositoryInterface;

interface JobApplicationRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get applications for a specific job posting.
     */
    public function getByPosting(int $postingId);

    /**
     * Get paginated applications with optional filters.
     */
    public function getPaginatedList(array $filters = [], int $perPage = 20);
}
