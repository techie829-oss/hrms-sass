<?php

namespace App\Modules\Attendance\Policies;

use App\Models\User;
use App\Modules\Attendance\Models\AttendanceLog;

class AttendanceLogPolicy
{
    /**
     * View attendance list.
     * - Everyone with an employee profile or admin roles can view.
     */
    public function viewAny(User $user): bool
    {
        return $user->employee !== null || $user->hasAnyRole(['tadmin', 'tmanager', 'superadmin']);
    }

    /**
     * View a specific attendance log.
     * - Employees: only their own
     * - Admin/Manager: any record in same tenant
     */
    public function view(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        // Admins can see everything in their tenant
        if ($user->hasAnyRole(['tadmin', 'tmanager', 'superadmin'])) {
            return true;
        }

        // Regular employees can only see their own logs
        return $user->employee?->id === $attendanceLog->employee_id;
    }

    /**
     * Create / log attendance.
     */
    public function create(User $user): bool
    {
        return $user->employee !== null || $user->hasAnyRole(['tadmin', 'tmanager', 'superadmin']);
    }

    /**
     * Manually update an attendance record (admin correction).
     */
    public function update(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasAnyRole(['tadmin', 'tmanager', 'superadmin']);
    }

    /**
     * Delete an attendance log — tadmin only.
     */
    public function delete(User $user, AttendanceLog $attendanceLog): bool
    {
        if ($attendanceLog->tenant_id !== $user->tenant_id) {
            return false;
        }

        return $user->hasAnyRole(['tadmin', 'superadmin']);
    }
}
