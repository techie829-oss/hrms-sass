<?php

namespace App\Modules\Leave\Services;

use App\Modules\Leave\DTOs\LeaveRequestData;
use App\Modules\Leave\DTOs\LeaveRequestBulkData;
use App\Modules\Leave\Models\LeaveRequest;
use App\Modules\Leave\Models\LeaveType;
use App\Modules\Leave\Models\LeaveBalance;
use App\Modules\Leave\Models\CompOffRequest;
use App\Modules\HR\Models\Employee;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Modules\Leave\Interfaces\LeaveRequestRepositoryInterface;

class LeaveRequestService
{

    public function __construct(
        protected LeaveCalculationService $calculationService,
        protected LeaveRequestRepositoryInterface $repository
    ) {}

    public function getPaginatedList(array $filters = [], int $perPage = 15)
    {
        return $this->repository->getPaginatedList($filters, $perPage);
    }

    /**
     * Create a single leave request using DTO.
     */
    public function createLeaveRequest(LeaveRequestData $data): LeaveRequest
    {
        $employee = Employee::findOrFail($data->employee_id);
        $leaveType = LeaveType::findOrFail($data->leave_type_id);

        if ($employee->is_on_probation && !$leaveType->applicable_in_probation) {
            throw new Exception("Account is in probation. {$leaveType->name} is not applicable.");
        }

        $startDateStr = $data->start_date->format('Y-m-d');
        $endDateStr = $data->end_date->format('Y-m-d');

        if ($data->is_half_day && $startDateStr !== $endDateStr) {
            throw new Exception("Half-day leave must be on a single day.");
        }

        $totalDays = $this->calculationService->calculateNetDays($startDateStr, $endDateStr, $data->employee_id);
        
        if ($data->is_half_day) {
            $totalDays = 0.5;
        }

        if ($totalDays <= 0) {
            throw new Exception("Selected dates are already holidays or weekends.");
        }

        $balance = LeaveBalance::where([
            'employee_id' => $data->employee_id,
            'leave_type_id' => $data->leave_type_id,
            'year' => now()->year,
        ])->first();

        if (!$balance) {
            throw new Exception("No leave balance allocated for this employee for " . now()->year . ".");
        }

        if ($balance->balance < $totalDays) {
            throw new Exception("Insufficient leave balance. Remaining: {$balance->balance} days.");
        }

        return DB::transaction(function () use ($data, $totalDays, $balance, $startDateStr, $endDateStr) {
            $leaveRequest = LeaveRequest::create([
                'tenant_id' => saas_tenant('id'),
                'employee_id' => $data->employee_id,
                'leave_type_id' => $data->leave_type_id,
                'start_date' => $startDateStr,
                'end_date' => $endDateStr,
                'total_days' => $totalDays,
                'is_half_day' => $data->is_half_day,
                'half_day_type' => $data->half_day_type,
                'reason' => $data->reason,
                'status' => 'pending',
            ]);

            LeaveBalance::where('id', $balance->id)->increment('total_pending', $totalDays);

            return $leaveRequest;
        });
    }

    /**
     * Create multiple leave requests at once using DTO.
     */
    public function createBulkLeaveRequests(LeaveRequestBulkData $data, int $approvedById): array
    {
        $successCount = 0;
        $errors = [];
        $startDateStr = $data->start_date->format('Y-m-d');
        $endDateStr = $data->end_date->format('Y-m-d');
        
        $employees = Employee::whereIn('id', $data->employee_ids)->get()->keyBy('id');
        $coType = LeaveType::where('code', 'CO')->first();

        foreach ($data->employee_ids as $employeeId) {
            $employee = $employees->get($employeeId);
            if (!$employee) {
                $errors[] = "Employee ID {$employeeId} not found.";
                continue;
            }

            $totalDays = $this->calculationService->calculateNetDays($startDateStr, $endDateStr, $employeeId);
            
            if ($totalDays <= 0) {
                $errors[] = "Employee {$employee->first_name}: Selected dates are holidays/weekends.";
                continue;
            }

            $leaveType = LeaveType::find($data->leave_type_id);
            if ($employee->is_on_probation && $leaveType && !$leaveType->applicable_in_probation) {
                $errors[] = "Employee {$employee->first_name} is in probation. {$leaveType->name} is not applicable.";
                continue;
            }
            
            $balance = LeaveBalance::where([
                'employee_id' => $employeeId,
                'leave_type_id' => $data->leave_type_id,
                'year' => $data->start_date->format('Y'),
            ])->first();

            if (!$balance || $balance->balance < $totalDays) {
                $errors[] = "Employee {$employee->first_name} has insufficient balance.";
                continue;
            }

            DB::transaction(function () use ($data, $totalDays, $employeeId, $balance, $startDateStr, $endDateStr, $coType, $approvedById) {
                $leaveRequest = LeaveRequest::create([
                    'tenant_id' => saas_tenant('id'),
                    'employee_id' => $employeeId,
                    'leave_type_id' => $data->leave_type_id,
                    'start_date' => $startDateStr,
                    'end_date' => $endDateStr,
                    'total_days' => $totalDays,
                    'is_half_day' => false,
                    'reason' => $data->reason,
                    'status' => 'approved', 
                    'applied_on' => now(),
                    'approved_by' => $approvedById,
                    'approved_at' => now(),
                ]);

                LeaveBalance::where('id', $balance->id)->decrement('balance', $totalDays);
                LeaveBalance::where('id', $balance->id)->increment('total_used', $totalDays);

                if ($coType && $data->leave_type_id === $coType->id) {
                    $unsettled = CompOffRequest::where('employee_id', $employeeId)
                        ->where('status', 'approved')
                        ->where('is_used', false)
                        ->orderBy('worked_on_date')
                        ->limit(ceil($totalDays))
                        ->get();

                    foreach ($unsettled as $co) {
                        CompOffRequest::where('id', $co->id)->update([
                            'is_used' => true,
                            'used_at' => $startDateStr,
                            'leave_request_id' => $leaveRequest->id
                        ]);
                    }
                }
            });

            $successCount++;
        }

        return [
            'success_count' => $successCount,
            'errors' => $errors
        ];
    }

    /**
     * Approve or reject a leave request.
     */
    public function updateStatus(LeaveRequest $leaveRequest, array $validatedData, int $approvedById): void
    {
        DB::transaction(function () use ($leaveRequest, $validatedData, $approvedById) {
            $oldStatus = $leaveRequest->status;
            
            $this->repository->update($leaveRequest->id, [
                'status' => $validatedData['status'],
                'rejection_reason' => $validatedData['rejection_reason'] ?? null,
                'approved_by' => $approvedById,
                'approved_at' => now(),
            ]);

            if ($oldStatus === 'pending') {
                $balance = LeaveBalance::where([
                    'employee_id' => $leaveRequest->employee_id,
                    'leave_type_id' => $leaveRequest->leave_type_id,
                    'year' => $leaveRequest->start_date->year,
                ])->first();

                if ($balance) {
                    LeaveBalance::where('id', $balance->id)->decrement('total_pending', $leaveRequest->total_days);
                    
                    if ($validatedData['status'] === 'approved') {
                        LeaveBalance::where('id', $balance->id)->decrement('balance', $leaveRequest->total_days);
                        LeaveBalance::where('id', $balance->id)->increment('total_used', $leaveRequest->total_days);

                        // Comp-Off Settlement Logic
                        $coType = LeaveType::where('code', 'CO')->first();
                        if ($coType && $leaveRequest->leave_type_id === $coType->id) {
                            $unsettled = CompOffRequest::where('employee_id', $leaveRequest->employee_id)
                                ->where('status', 'approved')
                                ->where('is_used', false)
                                ->orderBy('worked_on_date')
                                ->limit((int)$leaveRequest->total_days)
                                ->get();

                            foreach ($unsettled as $co) {
                                CompOffRequest::where('id', $co->id)->update([
                                    'is_used' => true,
                                    'used_at' => $leaveRequest->start_date,
                                    'leave_request_id' => $leaveRequest->id
                                ]);
                            }
                        }
                    }
                }
            }
        });
    }
}
