<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * RULE: Whenever you add a new Policy or @can() check in any Controller/Blade,
     * you MUST add the corresponding permission here in snake_case format.
     * Format: strtolower(preg_replace('/[\s\-]+/', '_', trim($name)))
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 1. SAAS INTERNAL ROLES (tenant_id = null) ---
        setPermissionsTeamId(null);

        $saasRoles = [
            'superadmin' => 'Full SaaS System Access',
            'sadmin'     => 'SaaS Administrator',
            'smanager'   => 'Sales & Marketing Manager',
            'sstaff'     => 'Sales & Marketing Staff',
        ];

        foreach ($saasRoles as $name => $description) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // --- 2. GLOBAL PERMISSIONS (snake_case, grouped by module) ---
        // Tenant roles (tadmin/tmanager/tstaff) are created per-tenant
        // during onboarding via App\Listeners\SetupTenantBlueprint.

        $permissions = [

            // -- DASHBOARD --------------------------------------------------
            'core_dashboard_view'         => 'Main Admin & Staff Dashboard Access',

            // -- EMPLOYEES (HR) ---------------------------------------------
            'hr_employee_view'         => 'List Employees, View Profiles, Directory',
            'hr_employee_create'       => 'Onboard Employees, Create User Accounts',
            'hr_employee_edit'         => 'Update Employee Information, Salary, Contacts',
            'hr_employee_delete'       => 'Terminate Employee, Purge User Account',
            'hr_department_view'       => 'List Departments, View Head of Departments',
            'hr_department_manage'     => 'Create, Edit, and Delete Departments',
            'hr_designation_view'      => 'List Designations',
            'hr_designation_manage'    => 'Create, Edit, and Delete Designations',

            // -- ATTENDANCE -------------------------------------------------
            'attendance_log_view_team'        => 'View Team Attendance Logs',
            'attendance_log_view_own'    => 'View Personal Attendance Logs',
            'attendance_log_view_all'    => 'View All Employees Attendance Logs',
            'attendance_log_manage'      => 'Approve/Correct Attendance Logs, Configure Shifts',

            // -- LEAVE ------------------------------------------------------
            'leave_request_create'           => 'Submit New Leave Requests, Apply for Time-Off',
            'leave_request_cancel'           => 'Cancel Pending/Approved Leave Requests',
            'leave_request_manage'           => 'Manage Leave Requests',
            'leave_request_view_own'         => 'View Personal Leave History',
            'core_settings_manage'        => 'Full System Configuration Access (Leave Types etc.)',

            // -- HOLIDAYS ---------------------------------------------------
            'leave_holiday_view'          => 'View Company Holiday Calendar',
            'leave_holiday_manage'        => 'Create, Edit, and Delete Holidays',

            // -- COMP-OFF ---------------------------------------------------
            'leave_compoff_view_team'          => 'View Team Compensatory Off Requests & Balances',
            'leave_compoff_view_own'      => 'View Personal Compensatory Off History',
            'leave_compoff_create'        => 'Submit Personal Comp-Off Requests',
            'leave_compoff_manage'        => 'Approve/Reject Comp-Off Earnings',

            // -- PAYROLL ----------------------------------------------------
            'payroll_run_view_team'           => 'View Team Payslips',
            'payroll_run_view_own'       => 'View Personal Payslips',
            'payroll_run_manage'         => 'Process Monthly Salaries, Generate Payslips',

            // -- OPERATIONS (Leads / Projects / Tasks) ----------------------
            'operations_lead_view'             => 'View Leads and Clients List',
            'operations_lead_manage'           => 'Create, Update, and Delete Leads & Clients',
            'operations_project_view'          => 'View Projects List and Details',
            'operations_project_manage'        => 'Create, Update, Assign, and Delete Projects',
            'operations_task_view'             => 'View Tasks Assigned or Available',
            'operations_task_manage'           => 'Create, Update, Assign, and Delete Tasks',

            // -- TIMESHEET --------------------------------------------------
            'timesheet_log_view_team'         => 'View Team Timesheet & Activity Logs',
            'timesheet_log_view_own'     => 'Access Personal Timesheet & Activity Logs',
            'timesheet_log_manage'       => 'Submit, Edit, and Approve Timesheet Hours',

            // -- PERFORMANCE ------------------------------------------------
            'performance_appraisal_view_team'       => 'View Team Goals & KPIs',
            'performance_appraisal_view_own'   => 'View Personal Goals & Reviews',
            'performance_appraisal_manage'     => 'Define KPIs, Submit Staff Appraisals & Reviews',

            // -- RECRUITMENT ------------------------------------------------
            'recruitment_job_view'       => 'View Job Postings and Applications',
            'recruitment_job_manage'     => 'Create Job Postings, Manage Applications & Pipeline',

            // -- REPORTS ----------------------------------------------------
            'reports_analytics_view'           => 'Access Analytics, Staff Demographics, & Attendance Reports',
        ];

        foreach ($permissions as $permission => $description) {
            $perm = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            $perm->update(['description' => $description]);
        }
    }
}
