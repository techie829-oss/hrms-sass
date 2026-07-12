<?php

namespace App\Core\Constants;

/**
 * Global Permission Name Constants for the HRMS SaaS Platform.
 *
 * RULES:
 * - All values MUST be snake_case (lowercase with underscores).
 * - When adding a new permission here, you MUST also add it to:
 *   1. database/seeders/RoleSeeder.php  (creates it in DB)
 *   2. app/Listeners/SetupTenantBlueprint.php  (assigns it to the correct tenant role)
 * - Use these constants everywhere in Policies, Controllers, and Blade via @can().
 *   Never hardcode a permission string directly.
 */
class PermissionConstants
{
    // ── DASHBOARD ──────────────────────────────────────────────────────────
    public const VIEW_DASHBOARD         = 'core_dashboard_view';

    // ── EMPLOYEES (HR) ─────────────────────────────────────────────────────
    public const VIEW_EMPLOYEES         = 'hr_employee_view';
    public const CREATE_EMPLOYEES       = 'hr_employee_create';
    public const EDIT_EMPLOYEES         = 'hr_employee_edit';
    public const DELETE_EMPLOYEES       = 'hr_employee_delete';
    public const VIEW_DEPARTMENTS       = 'hr_department_view';
    public const MANAGE_DEPARTMENTS     = 'hr_department_manage';
    public const VIEW_DESIGNATIONS      = 'hr_designation_view';
    public const MANAGE_DESIGNATIONS    = 'hr_designation_manage';

    // ── ATTENDANCE ─────────────────────────────────────────────────────────
    public const VIEW_ATTENDANCE        = 'attendance_log_view_team';
    public const VIEW_OWN_ATTENDANCE    = 'attendance_log_view_own';
    public const VIEW_ALL_ATTENDANCE    = 'attendance_log_view_all';
    public const MANAGE_ATTENDANCE      = 'attendance_log_manage';

    // ── LEAVE ──────────────────────────────────────────────────────────────
    public const CREATE_LEAVE           = 'leave_request_create';
    public const CANCEL_LEAVE           = 'leave_request_cancel';
    public const MANAGE_LEAVE           = 'leave_request_manage';
    public const VIEW_OWN_LEAVE         = 'leave_request_view_own';

    // ── HOLIDAYS ───────────────────────────────────────────────────────────
    public const VIEW_HOLIDAYS          = 'leave_holiday_view';
    public const MANAGE_HOLIDAYS        = 'leave_holiday_manage';

    // ── COMP-OFF ───────────────────────────────────────────────────────────
    public const VIEW_COMP_OFF          = 'leave_compoff_view_team';
    public const VIEW_OWN_COMP_OFF      = 'leave_compoff_view_own';
    public const CREATE_COMP_OFF        = 'leave_compoff_create';
    public const MANAGE_COMP_OFF        = 'leave_compoff_manage';

    // ── PAYROLL ────────────────────────────────────────────────────────────
    public const VIEW_PAYROLL           = 'payroll_run_view_team';
    public const VIEW_OWN_PAYROLL       = 'payroll_run_view_own';
    public const MANAGE_PAYROLL         = 'payroll_run_manage';

    // ── OPERATIONS (Leads / Projects / Tasks) ──────────────────────────────
    public const VIEW_LEADS             = 'operations_lead_view';
    public const MANAGE_LEADS           = 'operations_lead_manage';
    public const VIEW_PROJECTS          = 'operations_project_view';
    public const MANAGE_PROJECTS        = 'operations_project_manage';
    public const VIEW_TASKS             = 'operations_task_view';
    public const MANAGE_TASKS           = 'operations_task_manage';

    // ── TIMESHEET ──────────────────────────────────────────────────────────
    public const VIEW_TIMESHEET         = 'timesheet_log_view_team';
    public const VIEW_OWN_TIMESHEET     = 'timesheet_log_view_own';
    public const MANAGE_TIMESHEET       = 'timesheet_log_manage';

    // ── PERFORMANCE ────────────────────────────────────────────────────────
    public const VIEW_PERFORMANCE       = 'performance_appraisal_view_team';
    public const VIEW_OWN_PERFORMANCE   = 'performance_appraisal_view_own';
    public const MANAGE_PERFORMANCE     = 'performance_appraisal_manage';

    // ── RECRUITMENT ────────────────────────────────────────────────────────
    public const VIEW_RECRUITMENT       = 'recruitment_job_view';
    public const MANAGE_RECRUITMENT     = 'recruitment_job_manage';

    // ── REPORTS ────────────────────────────────────────────────────────────
    public const VIEW_REPORTS           = 'reports_analytics_view';

    // ── SETTINGS ───────────────────────────────────────────────────────────
    public const MANAGE_SETTINGS        = 'core_settings_manage';

    /**
     * Get a structured array of permissions grouped by Module for UI rendering.
     * Maps the raw permission strings to human-readable labels.
     */
    public static function getPermissionDetails(): array
    {
        return [
            'Dashboard' => [
                self::VIEW_DASHBOARD => 'View Dashboard',
            ],
            'Employees' => [
                self::VIEW_EMPLOYEES => 'View Employees',
                self::CREATE_EMPLOYEES => 'Create Employee',
                self::EDIT_EMPLOYEES => 'Edit Employee',
                self::DELETE_EMPLOYEES => 'Delete Employee',
                self::VIEW_DEPARTMENTS => 'View Departments',
                self::MANAGE_DEPARTMENTS => 'Manage Departments',
                self::VIEW_DESIGNATIONS => 'View Designations',
                self::MANAGE_DESIGNATIONS => 'Manage Designations',
            ],
            'Attendance' => [
                self::MANAGE_ATTENDANCE => 'Full Control (Manage All)',
                self::VIEW_ALL_ATTENDANCE => 'View All Attendance',
                self::VIEW_ATTENDANCE => 'View Team Attendance',
                self::VIEW_OWN_ATTENDANCE => 'View Own Attendance',
            ],
            'Leave' => [
                self::MANAGE_LEAVE => 'Full Control (Manage All)',
                self::VIEW_OWN_LEAVE => 'View Own Leaves',
                self::CREATE_LEAVE => 'Create Leave Request',
                self::CANCEL_LEAVE => 'Cancel Leave Request',
            ],
            'Comp-Off' => [
                self::MANAGE_COMP_OFF => 'Full Control (Manage All)',
                self::VIEW_COMP_OFF => 'View Team Comp-Off',
                self::VIEW_OWN_COMP_OFF => 'View Own Comp-Off',
                self::CREATE_COMP_OFF => 'Create Comp-Off Request',
            ],
            'Holidays' => [
                self::MANAGE_HOLIDAYS => 'Full Control (Manage)',
                self::VIEW_HOLIDAYS => 'View Holidays',
            ],
            'Payroll' => [
                self::MANAGE_PAYROLL => 'Full Control (Manage All)',
                self::VIEW_PAYROLL => 'View Team Payroll',
                self::VIEW_OWN_PAYROLL => 'View Own Payroll',
            ],
            'Operations' => [
                self::MANAGE_LEADS => 'Manage Leads',
                self::VIEW_LEADS => 'View Leads',
                self::MANAGE_PROJECTS => 'Manage Projects',
                self::VIEW_PROJECTS => 'View Projects',
                self::MANAGE_TASKS => 'Manage Tasks',
                self::VIEW_TASKS => 'View Tasks',
            ],
            'Timesheet' => [
                self::MANAGE_TIMESHEET => 'Full Control (Manage All)',
                self::VIEW_TIMESHEET => 'View Team Timesheet',
                self::VIEW_OWN_TIMESHEET => 'View Own Timesheet',
            ],
            'Performance' => [
                self::MANAGE_PERFORMANCE => 'Full Control (Manage All)',
                self::VIEW_PERFORMANCE => 'View Team Performance',
                self::VIEW_OWN_PERFORMANCE => 'View Own Performance',
            ],
            'Recruitment' => [
                self::MANAGE_RECRUITMENT => 'Full Control (Manage All)',
                self::VIEW_RECRUITMENT => 'View Recruitment',
            ],
            'Reports' => [
                self::VIEW_REPORTS => 'View Reports',
            ],
            'Settings' => [
                self::MANAGE_SETTINGS => 'Full System Settings Access',
            ],
        ];
    }
}
