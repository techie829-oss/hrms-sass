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

        // 1. Define Roles
        $roles = [
            'super_admin' => 'Full System Access',
            'hr_manager' => 'HR Management Access',
            'manager' => 'Team Management Access',
            'employee' => 'Self-Service Access',
        ];

        foreach ($roles as $name => $description) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // 2. Define Permissions (Core)
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
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 3. Assign Permissions to Roles
        Role::findByName('hr_manager')->givePermissionTo([
            'view dashboard', 'view employees', 'create employees', 'edit employees',
            'view departments', 'manage attendance', 'approve leave', 'generate payroll', 'view reports',
        ]);

        Role::findByName('manager')->givePermissionTo([
            'view dashboard', 'view employees', 'manage attendance', 'approve leave', 'view reports',
        ]);

        Role::findByName('employee')->givePermissionTo([
            'view dashboard',
        ]);
    }
}
