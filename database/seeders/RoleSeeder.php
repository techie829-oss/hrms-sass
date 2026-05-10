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
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 1. SAAS INTERNAL ROLES (tenant_id = null) ---
        // These roles are for managing the platform itself.
        setPermissionsTeamId(null);

        $saasRoles = [
            'superadmin' => 'Full SaaS System Access',
            'sadmin' => 'SaaS Administrator',
            'smanager' => 'Sales & Marketing Manager',
            'sstaff' => 'Sales & Marketing Staff',
        ];

        foreach ($saasRoles as $name => $description) {
            Role::firstOrCreate([
                'name' => $name, 
                'guard_name' => 'web',
            ]);
        }

        // --- 2. TENANT BASELINE ROLES (Used as templates or seeded per tenant) ---
        // For existing tenants or demo purposes, we can seed them with a specific tenant_id if needed,
        // but typically these are created during Tenant Creation/Onboarding.
        
        $tenantRoles = [
            'tadmin' => 'Tenant Administrator',
            'tmanager' => 'HR/Payroll Manager',
            'tstaff' => 'Employee',
        ];

        // Permissions for Tenants
        $permissions = [
            'view-dashboard' => 'Main Admin & Staff Dashboard Access',
            'view-employees' => 'List Employees, View Profiles, Directory',
            'create-employees' => 'Onboard Employees, Create User Accounts',
            'edit-employees' => 'Update Employee Information, Salary, Contacts',
            'delete-employees' => 'Terminate Employee, Purge User Account',
            'view-departments' => 'List Departments, View Head of Departments',
            'manage-attendance' => 'Approve/Correct Attendance Logs, Configure Shifts',
            'view-attendance' => 'View Team Attendance Logs',
            'view-own-attendance' => 'View Personal Attendance Logs',
            'approve-leave' => 'Approve/Reject Leave Requests',
            'create-leave' => 'Submit New Leave Requests, Apply for Time-Off',
            'view-own-leave' => 'View Personal Leave History',
            'cancel-leave' => 'Cancel Pending/Approved Leave Requests',
            'generate-payroll' => 'Process Monthly Salaries, Generate Payslips',
            'view-payroll' => 'View Team Payslips',
            'view-own-payroll' => 'View Personal Payslips',
            'view-reports' => 'Access Analytics, Staff Demographics, & Attendance Reports',
            'view-performance' => 'View Team Goals & KPIs',
            'view-own-performance' => 'View Personal Goals & Reviews',
            'manage-performance' => 'Define KPIs, Submit Staff Appraisals & Reviews',
            'view-timesheet' => 'Access Team Timesheet & Activity Logs',
            'view-own-timesheet' => 'Access Personal Timesheet & Activity Logs',
            'manage-timesheet' => 'Submit, Edit, and Approve Timesheet Hours',
            'view-holidays' => 'View Company Holiday Calendar',
            'manage-holidays' => 'Create, Edit, and Delete Holidays',
            'view-comp-off' => 'View Team Compensatory Off Requests & Balances',
            'view-own-comp-off' => 'View Personal Compensatory Off History',
            'create-comp-off' => 'Submit Personal Comp-Off Requests',
            'manage-comp-off' => 'Approve/Reject Comp-Off Earnings',
            'manage-settings' => 'Full System Configuration Access',
        ];

        foreach ($permissions as $permission => $description) {
            $perm = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            $perm->update(['description' => $description]);
        }

        // Note: For now, we seed the global SaaS roles. 
        // Tenant roles should be seeded when a tenant is created in the TenantService/ModuleManager.
    }
}
