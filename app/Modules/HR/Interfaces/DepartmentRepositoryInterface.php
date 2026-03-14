<?php

namespace App\Modules\HR\Interfaces;

use App\Modules\HR\Models\Department;
use Illuminate\Database\Eloquent\Collection;

interface DepartmentRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function find(int|string $id): ?Department;

    public function findOrFail(int|string $id): Department;

    public function create(array $data): Department;

    public function update(int|string $id, array $data): Department;

    public function delete(int|string $id): bool;

    public function getAllWithMemberCount(): Collection;
}
