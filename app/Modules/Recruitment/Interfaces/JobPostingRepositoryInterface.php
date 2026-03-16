<?php

namespace App\Modules\Recruitment\Interfaces;

use App\Core\BaseRepositoryInterface;

interface JobPostingRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get all active job postings.
     */
    public function getActivePostings();
}
