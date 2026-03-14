<?php

namespace App\Modules\Attendance\Services;

use App\Core\BaseService;
use App\Modules\Attendance\Interfaces\AttendanceRepositoryInterface;

class AttendanceService extends BaseService
{
    public function __construct(AttendanceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get paginated logs.
     */
    public function paginate(int $perPage = 15, array $filters = [])
    {
        return $this->repository->paginate($perPage, $filters);
    }

    /**
     * Check in an employee.
     */
    public function checkIn(int $employeeId, array $data)
    {
        return $this->repository->checkIn($employeeId, $data);
    }

    /**
     * Check out an employee.
     */
    public function checkOut(int $employeeId, array $data)
    {
        return $this->repository->checkOut($employeeId, $data);
    }
}
