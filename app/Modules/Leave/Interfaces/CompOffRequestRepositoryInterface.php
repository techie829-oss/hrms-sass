<?php

namespace App\Modules\Leave\Interfaces;

use App\Core\BaseRepositoryInterface;
use App\Modules\Leave\Models\CompOffRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CompOffRequestRepositoryInterface extends BaseRepositoryInterface
{
    public function getPaginatedList(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function getEarnedCompOffs(string $date, int $tenantId): Collection;
    public function getUnsettledCompOffs(int $employeeId, int $limit): Collection;
}
