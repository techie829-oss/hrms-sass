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
        return $user->can('view_all_attendance') || $user->can('view_own_attendance');
    }

    /**
     * View a specific attendance log.
     */
    public function view(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->can('view_all_attendance')) {
            return true;
        }

        if ($user->can('view_own_attendance')) {
            return $user->employee?->id === $attendanceLog->employee_id;
        }

        return false;
    }

    /**
     * Create / log attendance (Manual Log).
     */
    public function create(User $user): bool
    {
        return $user->can('manage_attendance');
    }

    /**
     * Manually update an attendance record.
     */
    public function update(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->can('manage_attendance');
    }

    /**
     * Delete an attendance log.
     */
    public function delete(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->can('manage_attendance');
    }
}
