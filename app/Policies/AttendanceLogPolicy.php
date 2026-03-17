<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Attendance\Models\AttendanceLog;

class AttendanceLogPolicy
{
    /**
     * View attendance list.
     * - tstaff: allowed (filtered to own records in controller)
     * - tadmin/tmanager: allowed (all records in tenant)
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['tadmin', 'tmanager', 'tstaff']);
    }

    /**
     * View a specific attendance log.
     * - tstaff: only their own
     * - tadmin/tmanager: any record in same tenant
     */
    public function view(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        if ($user->hasRole('tstaff')) {
            return $user->employee?->id === $attendanceLog->employee_id;
        }

        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    /**
     * Create / log attendance.
     * All tenant users can clock in/out (e.g., via kiosk).
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['tadmin', 'tmanager', 'tstaff']);
    }

    /**
     * Manually update an attendance record (admin correction).
     */
    public function update(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasAnyRole(['tadmin', 'tmanager']);
    }

    /**
     * Delete an attendance log — tadmin only.
     */
    public function delete(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasRole('tadmin');
    }
}
