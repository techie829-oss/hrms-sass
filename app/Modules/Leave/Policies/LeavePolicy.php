<?php

namespace App\Modules\Leave\Policies;

use App\Models\User;
use App\Modules\Leave\Models\LeaveRequest;
use App\Core\Constants\RoleConstants;

class LeavePolicy
{
    /**
     * View list of leave requests.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['view leave', 'approve leave', 'create leave']);
    }

    /**
     * View a specific leave request.
     */
    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($leaveRequest->tenant_id !== $user->tenant_id) {
            return false;
        }

        // Staff can only view their own
        if ($user->hasRole(RoleConstants::TSTAFF) && !$user->hasPermissionTo('approve leave')) {
            return $user->employee?->id === $leaveRequest->employee_id;
        }

        return $user->hasAnyPermission(['approve leave', 'view leave']);
    }

    /**
     * Apply for a new leave request.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create leave');
    }

    /**
     * Approve or reject a leave request.
     */
    public function approve(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($leaveRequest->tenant_id !== $user->tenant_id) {
            return false;
        }

        // Prevent self-approval
        if ($user->employee?->id === $leaveRequest->employee_id) {
            return false;
        }

        return $user->hasPermissionTo('approve leave');
    }

    /**
     * Cancel a leave request.
     */
    public function cancel(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($leaveRequest->tenant_id !== $user->tenant_id) {
            return false;
        }

        // Staff can cancel their own if pending
        if ($user->employee?->id === $leaveRequest->employee_id) {
            return $leaveRequest->status === 'pending';
        }

        return $user->hasPermissionTo('cancel leave');
    }
}
