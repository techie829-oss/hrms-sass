<?php

namespace App\Modules\Leave\Services;

use App\Modules\Leave\Models\CompOffRequest;
use App\Modules\HR\Models\Employee;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Leave\Models\LeaveType;
use App\Modules\Leave\Models\LeaveRequest;
use App\Modules\Leave\DTOs\CompOffData;
use Illuminate\Support\Facades\DB;

use App\Modules\Leave\Interfaces\CompOffRequestRepositoryInterface;

class CompOffService
{
    public function __construct(
        private readonly LeaveBalanceService $balanceService,
        private readonly CompOffRequestRepositoryInterface $repository
    ) {}

    public function getPaginatedList(array $filters = [], int $perPage = 15)
    {
        return $this->repository->getPaginatedList($filters, $perPage);
    }

    public function createRequest(CompOffData $dto): CompOffRequest
    {
        return $this->repository->create($dto->toArray());
    }

    public function bulkGrant(string $date, string $reason, int $approvedBy, int $tenantId): int
    {
        $presentEmployees = AttendanceLog::where('date', $date)
            ->where('tenant_id', $tenantId)
            ->pluck('employee_id')
            ->unique();

        $count = 0;
        foreach ($presentEmployees as $employeeId) {
            $employee = Employee::find($employeeId);
            if (!$employee) continue;

            $this->repository->create([
                'tenant_id' => $tenantId,
                'employee_id' => $employeeId,
                'worked_on_date' => $date,
                'duration' => 1.0,
                'reason' => $reason,
                'status' => 'approved',
                'approved_by' => $approvedBy,
                'approved_at' => now(),
            ]);

            $this->balanceService->adjustBalance($employee, 'CO', 1.0);
            $count++;
        }

        return $count;
    }

    public function updateStatus(CompOffRequest $compOff, string $status, int $approvedBy): void
    {
        DB::transaction(function () use ($compOff, $status, $approvedBy) {
            $this->repository->update($compOff->id, [
                'status' => $status,
                'approved_by' => $approvedBy,
                'approved_at' => now(),
            ]);

            if ($status === 'approved') {
                $this->balanceService->adjustBalance($compOff->employee, 'CO', (float)$compOff->duration);
            }
        });
    }

    public function settleBulk(string $referenceDate, string $targetDate, int $approvedBy, int $tenantId): array
    {
        $earnedCompOffs = $this->repository->getEarnedCompOffs($referenceDate, $tenantId);

        $count = 0;
        $skipped = 0;

        foreach ($earnedCompOffs as $co) {
            $employee = $co->employee;

            $isPresent = AttendanceLog::where('employee_id', $co->employee_id)
                ->where('date', $targetDate)
                ->exists();

            if ($isPresent) {
                $skipped++;
                continue;
            }

            DB::transaction(function () use ($co, $employee, $targetDate, $referenceDate, $approvedBy, $tenantId) {
                $coType = LeaveType::where('code', 'CO')->first();
                
                $leaveRequest = LeaveRequest::create([
                    'tenant_id' => $tenantId,
                    'employee_id' => $co->employee_id,
                    'leave_type_id' => $coType->id,
                    'start_date' => $targetDate,
                    'end_date' => $targetDate,
                    'total_days' => 1.0,
                    'reason' => "Settlement for work on " . $referenceDate,
                    'status' => 'approved',
                    'approved_by' => $approvedBy,
                    'approved_at' => now(),
                ]);

                $this->repository->update($co->id, [
                    'is_used' => true,
                    'used_at' => $targetDate,
                    'leave_request_id' => $leaveRequest->id
                ]);

                $this->balanceService->adjustBalance($employee, 'CO', -1.0);
            });

            $count++;
        }

        return ['settled' => $count, 'skipped' => $skipped];
    }
}
