<?php

namespace App\Modules\Leave\Policies;

use App\Models\User;
use App\Modules\Leave\Models\LeaveRequest;
use App\Core\Constants\RoleConstants;
use App\Core\Constants\PermissionConstants;

class LeavePolicy
{
    /**
     * View list of leave requests.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            PermissionConstants::VIEW_OWN_LEAVE,
            PermissionConstants::MANAGE_LEAVE,
            PermissionConstants::CREATE_LEAVE,
        ]);
    }

    /**
     * View a specific leave request.
     */
    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($leaveRequest->tenant_id !== $user->tenant_id) {
            return false;
        }

        // Staff can only view their own if they have view_own-leave
        if ($user->hasPermissionTo(PermissionConstants::VIEW_OWN_LEAVE) && !$user->hasPermissionTo(PermissionConstants::MANAGE_LEAVE)) {
            return $user->employee?->id === $leaveRequest->employee_id;
        }

        return $user->hasAnyPermission([
            PermissionConstants::MANAGE_LEAVE,
            PermissionConstants::VIEW_OWN_LEAVE,
        ]);
    }

    /**
     * Apply for a new leave request.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::CREATE_LEAVE);
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

        return $user->hasPermissionTo(PermissionConstants::MANAGE_LEAVE);
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

        return $user->hasPermissionTo(PermissionConstants::CANCEL_LEAVE);
    }
}
