<?php

namespace App\Modules\Attendance\Policies;

use App\Models\User;
use App\Modules\Attendance\Models\AttendanceLog;

class AttendanceLogPolicy
{
    /**
     * View attendance list.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view attendance');
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
        if ($user->hasPermissionTo('manage attendance')) {
            return true;
        }

        // Staff can only view their own
        if ($user->hasPermissionTo('view attendance')) {
            return $user->employee?->id === $attendanceLog->employee_id;
        }

        return false;
    }

    /**
     * Create / log attendance (Manual Log).
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage attendance');
    }

    /**
     * Manually update an attendance record.
     */
    public function update(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo('manage attendance');
    }

    /**
     * Delete an attendance log.
     */
    public function delete(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo('manage attendance');
    }
}
