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
    public const VIEW_DASHBOARD         = 'view_dashboard';

    // ── EMPLOYEES (HR) ─────────────────────────────────────────────────────
    public const VIEW_EMPLOYEES         = 'view_employees';
    public const CREATE_EMPLOYEES       = 'create_employees';
    public const EDIT_EMPLOYEES         = 'edit_employees';
    public const DELETE_EMPLOYEES       = 'delete_employees';
    public const VIEW_DEPARTMENTS       = 'view_departments';

    // ── ATTENDANCE ─────────────────────────────────────────────────────────
    public const VIEW_ATTENDANCE        = 'view_attendance';
    public const VIEW_OWN_ATTENDANCE    = 'view_own_attendance';
    public const VIEW_ALL_ATTENDANCE    = 'view_all_attendance';
    public const MANAGE_ATTENDANCE      = 'manage_attendance';

    // ── LEAVE ──────────────────────────────────────────────────────────────
    public const CREATE_LEAVE           = 'create_leave';
    public const CANCEL_LEAVE           = 'cancel_leave';
    public const MANAGE_LEAVE           = 'manage_leave';
    public const VIEW_OWN_LEAVE         = 'view_own_leave';

    // ── HOLIDAYS ───────────────────────────────────────────────────────────
    public const VIEW_HOLIDAYS          = 'view_holidays';
    public const MANAGE_HOLIDAYS        = 'manage_holidays';

    // ── COMP-OFF ───────────────────────────────────────────────────────────
    public const VIEW_COMP_OFF          = 'view_comp_off';
    public const VIEW_OWN_COMP_OFF      = 'view_own_comp_off';
    public const CREATE_COMP_OFF        = 'create_comp_off';
    public const MANAGE_COMP_OFF        = 'manage_comp_off';

    // ── PAYROLL ────────────────────────────────────────────────────────────
    public const VIEW_PAYROLL           = 'view_payroll';
    public const VIEW_OWN_PAYROLL       = 'view_own_payroll';
    public const MANAGE_PAYROLL         = 'manage_payroll';

    // ── OPERATIONS (Leads / Projects / Tasks) ──────────────────────────────
    public const VIEW_LEADS             = 'view_leads';
    public const MANAGE_LEADS           = 'manage_leads';
    public const VIEW_PROJECTS          = 'view_projects';
    public const MANAGE_PROJECTS        = 'manage_projects';
    public const VIEW_TASKS             = 'view_tasks';
    public const MANAGE_TASKS           = 'manage_tasks';

    // ── TIMESHEET ──────────────────────────────────────────────────────────
    public const VIEW_TIMESHEET         = 'view_timesheet';
    public const VIEW_OWN_TIMESHEET     = 'view_own_timesheet';
    public const MANAGE_TIMESHEET       = 'manage_timesheet';

    // ── PERFORMANCE ────────────────────────────────────────────────────────
    public const VIEW_PERFORMANCE       = 'view_performance';
    public const VIEW_OWN_PERFORMANCE   = 'view_own_performance';
    public const MANAGE_PERFORMANCE     = 'manage_performance';

    // ── RECRUITMENT ────────────────────────────────────────────────────────
    public const VIEW_RECRUITMENT       = 'view_recruitment';
    public const MANAGE_RECRUITMENT     = 'manage_recruitment';

    // ── REPORTS ────────────────────────────────────────────────────────────
    public const VIEW_REPORTS           = 'view_reports';

    // ── SETTINGS ───────────────────────────────────────────────────────────
    public const MANAGE_SETTINGS        = 'manage_settings';

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
