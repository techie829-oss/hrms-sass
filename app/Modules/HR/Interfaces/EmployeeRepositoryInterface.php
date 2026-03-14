<?php

namespace App\Modules\HR\Interfaces;

use App\Modules\HR\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface EmployeeRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function find(int|string $id): ?Employee;

    public function findOrFail(int|string $id): Employee;

    public function create(array $data): Employee;

    public function update(int|string $id, array $data): Employee;

    public function delete(int|string $id): bool;

    public function findByDepartment(int $departmentId): Collection;

    public function countActive(): int;
}
