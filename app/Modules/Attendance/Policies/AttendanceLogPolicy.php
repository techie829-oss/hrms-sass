<?php

namespace App\Modules\Attendance\Policies;

use App\Models\User;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Core\Constants\PermissionConstants;

class AttendanceLogPolicy
{
    /**
     * View attendance list.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::VIEW_ATTENDANCE);
    }

    /**
     * View a specific attendance log.
     */
    public function view(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        // Managers can view all
        if ($user->hasPermissionTo(PermissionConstants::MANAGE_ATTENDANCE)) {
            return true;
        }

        // Staff can only view their own
        if ($user->hasPermissionTo(PermissionConstants::VIEW_ATTENDANCE)) {
            return $user->employee?->id === $attendanceLog->employee_id;
        }

        return false;
    }

    /**
     * Create / log attendance (Manual Log).
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_ATTENDANCE);
    }

    /**
     * Manually update an attendance record.
     */
    public function update(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo(PermissionConstants::MANAGE_ATTENDANCE);
    }

    public function delete(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo(PermissionConstants::MANAGE_ATTENDANCE);
    }

    /**
     * Manage attendance settings, shifts, etc.
     */
    public function manage(User $user): bool
    {
        return $user->hasPermissionTo(PermissionConstants::MANAGE_ATTENDANCE);
    }
}
