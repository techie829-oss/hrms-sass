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

        // Assign to default roles (if they exist)
        $adminRoles = ['tadmin', 'tmanager', 'superadmin'];
        foreach ($adminRoles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($permissions);
            }
        }

        // tstaff/tuser -> view_own
        $staffRoles = ['tstaff', 'tuser'];
        foreach ($staffRoles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo(['view_own_attendance']);
            }
        }
    }
}
