<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AttendancePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all attendance permissions
        $permissions = [
            'view_all_attendance',
            'view_own_attendance',
            'manage_attendance',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Assign to ALL roles with these names across all tenants
        $adminRoles = ['tadmin', 'tmanager', 'superadmin'];
        foreach ($adminRoles as $roleName) {
            $roles = Role::where('name', $roleName)->get();
            foreach ($roles as $role) {
                try {
                    $role->givePermissionTo($permissions);
                } catch (\Exception $e) {
                    // Skip if error
                }
            }
        }

        // Assign to ALL tstaff roles
        $staffRoles = ['tstaff'];
        foreach ($staffRoles as $roleName) {
            $roles = Role::where('name', $roleName)->get();
            foreach ($roles as $role) {
                try {
                    $role->givePermissionTo(['view_own_attendance']);
                } catch (\Exception $e) {
                    // Skip if error
                }
            }
        }
    }
}
