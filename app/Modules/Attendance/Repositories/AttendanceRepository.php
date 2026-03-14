<?php

namespace App\Modules\Attendance\Repositories;

use App\Core\BaseRepository;
use App\Modules\Attendance\Interfaces\AttendanceRepositoryInterface;
use App\Modules\Attendance\Models\AttendanceLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AttendanceRepository extends BaseRepository implements AttendanceRepositoryInterface
{
    public function __construct(AttendanceLog $model)
    {
        $this->model = $model;
    }

    public function findByEmployee(int $employeeId, array $filters = []): Collection
    {
        $query = $this->model->where('employee_id', $employeeId);

        if (isset($filters['start_date'])) {
            $query->where('date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('date', '<=', $filters['end_date']);
        }

        return $query->get();
    }

    public function checkIn(int $employeeId, array $data): AttendanceLog
    {
        return $this->model->create(array_merge($data, [
            'employee_id' => $employeeId,
            'date' => $data['date'] ?? now()->toDateString(),
            'check_in' => $data['check_in'] ?? now()->toTimeString(),
            'status' => 'present',
        ]));
    }

    public function checkOut(int $employeeId, array $data): ?AttendanceLog
    {
        $date = $data['date'] ?? now()->toDateString();
        $log = $this->model->where('employee_id', $employeeId)
            ->where('date', $date)
            ->first();

        if ($log) {
            $log->update([
                'check_out' => $data['check_out'] ?? now()->toTimeString(),
                'check_out_ip' => $data['check_out_ip'] ?? null,
                'check_out_lat' => $data['check_out_lat'] ?? null,
                'check_out_lng' => $data['check_out_lng'] ?? null,
            ]);

            return $log->fresh();
        }

        return null;
    }

    // Override generic methods for explicit type hints
    public function find(int|string $id): ?AttendanceLog
    {
        return parent::find($id);
    }

    public function findOrFail(int|string $id): AttendanceLog
    {
        return parent::findOrFail($id);
    }

    public function create(array $data): AttendanceLog
    {
        return parent::create($data);
    }

    public function update(int|string $id, array $data): AttendanceLog
    {
        return parent::update($id, $data);
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return parent::paginate($perPage, $filters);
    }
}
