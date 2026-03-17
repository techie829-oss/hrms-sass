<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
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
            'super_admin' => 'Full SaaS System Access',
            'sadmin' => 'SaaS Administrator',
            'smanager' => 'Sales & Marketing Manager',
            'sstaff' => 'Sales & Marketing Staff',
        ];

        foreach ($saasRoles as $name => $description) {
            Role::firstOrCreate([
                'name' => $name, 
                'guard_name' => 'web',
                'tenant_id' => null
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
            'view dashboard',
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            'view departments',
            'manage attendance',
            'approve leave',
            'generate payroll',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web', 'tenant_id' => null]);
        }

        // Note: For now, we seed the global SaaS roles. 
        // Tenant roles should be seeded when a tenant is created in the TenantService/ModuleManager.
    }
}
