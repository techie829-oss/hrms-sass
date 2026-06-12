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
            'view_dashboard'         => 'Main Admin & Staff Dashboard Access',

            // -- EMPLOYEES (HR) ---------------------------------------------
            'view_employees'         => 'List Employees, View Profiles, Directory',
            'create_employees'       => 'Onboard Employees, Create User Accounts',
            'edit_employees'         => 'Update Employee Information, Salary, Contacts',
            'delete_employees'       => 'Terminate Employee, Purge User Account',
            'view_departments'       => 'List Departments, View Head of Departments',

            // -- ATTENDANCE -------------------------------------------------
            'view_attendance'        => 'View Team Attendance Logs',
            'view_own_attendance'    => 'View Personal Attendance Logs',
            'view_all_attendance'    => 'View All Employees Attendance Logs',
            'manage_attendance'      => 'Approve/Correct Attendance Logs, Configure Shifts',

            // -- LEAVE ------------------------------------------------------
            'create_leave'           => 'Submit New Leave Requests, Apply for Time-Off',
            'cancel_leave'           => 'Cancel Pending/Approved Leave Requests',
            'approve_leave'          => 'Approve/Reject Leave Requests',
            'view_own_leave'         => 'View Personal Leave History',
            'manage_settings'        => 'Full System Configuration Access (Leave Types etc.)',

            // -- HOLIDAYS ---------------------------------------------------
            'view_holidays'          => 'View Company Holiday Calendar',
            'manage_holidays'        => 'Create, Edit, and Delete Holidays',

            // -- COMP-OFF ---------------------------------------------------
            'view_comp_off'          => 'View Team Compensatory Off Requests & Balances',
            'view_own_comp_off'      => 'View Personal Compensatory Off History',
            'create_comp_off'        => 'Submit Personal Comp-Off Requests',
            'manage_comp_off'        => 'Approve/Reject Comp-Off Earnings',

            // -- PAYROLL ----------------------------------------------------
            'view_payroll'           => 'View Team Payslips',
            'view_own_payroll'       => 'View Personal Payslips',
            'manage_payroll'         => 'Process Monthly Salaries, Generate Payslips',

            // -- OPERATIONS (Leads / Projects / Tasks) ----------------------
            'view_leads'             => 'View Leads and Clients List',
            'manage_leads'           => 'Create, Update, and Delete Leads & Clients',
            'view_projects'          => 'View Projects List and Details',
            'manage_projects'        => 'Create, Update, Assign, and Delete Projects',
            'view_tasks'             => 'View Tasks Assigned or Available',
            'manage_tasks'           => 'Create, Update, Assign, and Delete Tasks',

            // -- TIMESHEET --------------------------------------------------
            'view_timesheet'         => 'View Team Timesheet & Activity Logs',
            'view_own_timesheet'     => 'Access Personal Timesheet & Activity Logs',
            'manage_timesheet'       => 'Submit, Edit, and Approve Timesheet Hours',

            // -- PERFORMANCE ------------------------------------------------
            'view_performance'       => 'View Team Goals & KPIs',
            'view_own_performance'   => 'View Personal Goals & Reviews',
            'manage_performance'     => 'Define KPIs, Submit Staff Appraisals & Reviews',

            // -- RECRUITMENT ------------------------------------------------
            'view_recruitment'       => 'View Job Postings and Applications',
            'manage_recruitment'     => 'Create Job Postings, Manage Applications & Pipeline',

            // -- REPORTS ----------------------------------------------------
            'view_reports'           => 'Access Analytics, Staff Demographics, & Attendance Reports',
        ];

        foreach ($permissions as $permission => $description) {
            $perm = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            $perm->update(['description' => $description]);
        }
    }
}
