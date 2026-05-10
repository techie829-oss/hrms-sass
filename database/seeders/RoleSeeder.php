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
            'view dashboard' => 'Main Admin & Staff Dashboard Access',
            'view employees' => 'List Employees, View Profiles, Directory',
            'create employees' => 'Onboard Employees, Create User Accounts',
            'edit employees' => 'Update Employee Information, Salary, Contacts',
            'delete employees' => 'Terminate Employee, Purge User Account',
            'view departments' => 'List Departments, View Head of Departments',
            'manage attendance' => 'Approve/Correct Attendance Logs, Configure Shifts',
            'view attendance' => 'View Individual Clock-In/Clock-Out logs',
            'approve leave' => 'Approve/Reject Leave Requests',
            'create leave' => 'Submit New Leave Requests, Apply for Time-Off',
            'cancel leave' => 'Cancel Pending/Approved Leave Requests',
            'generate payroll' => 'Process Monthly Salaries, Generate Payslips',
            'view payroll' => 'View Individual Payslips, Print Salary Slips',
            'view reports' => 'Access Analytics, Staff Demographics, & Attendance Reports',
            'view performance' => 'View Goals, Key Performance Indicators (KPIs)',
            'manage performance' => 'Define KPIs, Submit Staff Appraisals & Reviews',
            'view timesheet' => 'Access Personal Timesheet & Activity Logs',
            'manage timesheet' => 'Submit, Edit, and Approve Timesheet Hours',
            'view holidays' => 'View Company Holiday Calendar',
            'manage holidays' => 'Create, Edit, and Delete Holidays',
            'view comp_off' => 'View Compensatory Off Requests & Balances',
            'manage comp_off' => 'Approve/Reject Comp-Off Earnings',
        ];

        foreach ($permissions as $permission => $description) {
            $perm = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            $perm->update(['description' => $description]);
        }

        // Note: For now, we seed the global SaaS roles. 
        // Tenant roles should be seeded when a tenant is created in the TenantService/ModuleManager.
    }
}
