<?php

namespace App\Modules\Attendance\Interfaces;

use App\Modules\Attendance\Models\AttendanceLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AttendanceRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function find(int|string $id): ?AttendanceLog;

    public function findOrFail(int|string $id): AttendanceLog;

    public function create(array $data): AttendanceLog;

    public function update(int|string $id, array $data): AttendanceLog;

    public function delete(int|string $id): bool;

    public function findByEmployee(int $employeeId, array $filters = []): Collection;

    public function checkIn(int $employeeId, array $data): AttendanceLog;

    public function checkOut(int $employeeId, array $data): ?AttendanceLog;
}
