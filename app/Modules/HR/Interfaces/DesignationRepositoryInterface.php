<?php

namespace App\Modules\HR\Interfaces;

use App\Modules\HR\Models\Designation;
use Illuminate\Database\Eloquent\Collection;

interface DesignationRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function find(int|string $id): ?Designation;

    public function findOrFail(int|string $id): Designation;

    public function create(array $data): Designation;

    public function update(int|string $id, array $data): Designation;

    public function delete(int|string $id): bool;

    public function getAllWithEmployeeCount(): Collection;
}
