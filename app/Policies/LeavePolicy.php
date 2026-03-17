<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Leave\Models\LeaveRequest;

class LeavePolicy
{
    /**
     * View list of leave requests.
     * - tstaff: allowed (they'll see filtered results in controller)
     * - tadmin/tmanager: allowed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['tadmin', 'tmanager', 'tstaff']);
    }

    /**
     * View a specific leave request.
     * - tstaff: only their own
     * - tadmin/tmanager: any leave in same tenant
     */
    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($leaveRequest->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasRole('tstaff')) {
            return $user->employee?->id === $leaveRequest->employee_id;
        }

        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    /**
     * Apply for a new leave request.
     * All tenant users can apply.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['tadmin', 'tmanager', 'tstaff']);
    }

    /**
     * Approve or reject a leave request.
     * Only tadmin and tmanager can approve/reject.
     */
    public function approve(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($leaveRequest->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    /**
     * Cancel a leave request.
     * - tstaff: can cancel their own (if still pending)
     * - tadmin/tmanager: can cancel any
     */
    public function cancel(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($leaveRequest->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasRole('tstaff')) {
            return $user->employee?->id === $leaveRequest->employee_id
                && $leaveRequest->status === 'pending';
        }

        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }
}
