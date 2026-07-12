<?php

namespace App\Modules\Attendance\Repositories;

use App\Core\BaseRepository;
use App\Modules\Attendance\Interfaces\AttendanceRepositoryInterface;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\AttendanceDailySummary;
use App\Modules\Attendance\Models\AttendanceShift;
use App\Modules\HR\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

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

        if ($log instanceof AttendanceLog) {
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
        $query = $this->model->query()->with('employee');

        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'ilike', "%{$search}%")
                  ->orWhere('last_name', 'ilike', "%{$search}%")
                  ->orWhere('employee_id', 'ilike', "%{$search}%");
            });
            unset($filters['search']);
        }

        if (isset($filters['date']) && !empty($filters['date'])) {
            $query->where('date', $filters['date']);
            unset($filters['date']);
        }

        if (isset($filters['month']) && !empty($filters['month'])) {
            $query->whereMonth('date', date('m', strtotime($filters['month'])))
                  ->whereYear('date', date('Y', strtotime($filters['month'])));
            unset($filters['month']);
        }

        foreach ($filters as $field => $value) {
            if (!is_null($value) && $value !== '') {
                $query->where($field, $value);
            }
        }

        return $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getLogById(int $id): ?AttendanceLog
    {
        return $this->model->with('employee')->findOrFail($id);
    }

    public function getLogsByEmployeeAndDate(int $employeeId, string $date): Collection
    {
        return $this->model->where('employee_id', $employeeId)
            ->where('date', $date)
            ->orderBy('check_in', 'asc')
            ->get();
    }

    public function getSummaryQuery(array $filters): Collection
    {
        $summaryQuery = AttendanceDailySummary::query();
        
        if (isset($filters['employee_id'])) {
            $summaryQuery->where('employee_id', $filters['employee_id']);
        }
        
        if (isset($filters['month'])) {
            $carbon = Carbon::parse($filters['month']);
            $summaryQuery->whereYear('date', $carbon->year)->whereMonth('date', $carbon->month);
        } else {
            $summaryQuery->whereYear('date', date('Y'))->whereMonth('date', date('m'));
        }

        return $summaryQuery->get();
    }

    public function updateEmployeeShift(int $employeeId, ?int $shiftId): void
    {
        Employee::where('id', $employeeId)->update([
            'attendance_shift_id' => $shiftId,
        ]);
    }

    public function getActiveShifts(): Collection
    {
        return AttendanceShift::where('is_active', true)->get();
    }

    public function getEmployeesWithShifts(): Collection
    {
        return Employee::with('attendanceShift')->where('status', 'active')->get();
    }
}
