<?php

namespace App\Modules\Leave\Interfaces;

use App\Modules\Leave\Models\LeaveRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface LeaveRequestRepositoryInterface
{
    public function find(int|string $id): ?LeaveRequest;

    public function findOrFail(int|string $id): LeaveRequest;

    public function create(array $data): LeaveRequest;

    public function update(int|string $id, array $data): LeaveRequest;

    public function getPaginatedList(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
