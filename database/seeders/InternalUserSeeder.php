<?php

namespace Database\Seeders;

use App\Core\Constants\RoleConstants;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class InternalUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Foundational Permissions
        $permissions = [
            'view dashboard',
            'view employees',
            'manage attendance',
            'approve leave',
            'view reports',
            'manage projects',
            'manage payroll',
            'manage performance',
            'manage recruitment',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // 2. Create Essential SaaS Roles using Models
        $roles = [
            RoleConstants::SADMIN          => 'Primary SaaS Administrator',
            RoleConstants::SMANAGER        => 'SaaS Manager',
            RoleConstants::SSTAFF          => 'SaaS Staff (Support)',
            RoleConstants::TADMIN          => 'Tenant Administrator',
            RoleConstants::TMANAGER        => 'HR/Payroll Manager',
            RoleConstants::TSTAFF          => 'Employee',
        ];

        foreach ($roles as $name => $description) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // 2. Create Primary Super Admin User using Model
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@hrms.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'tenant_id' => null, // Global user
                'email_verified_at' => now(),
            ]
        );

        // 4. Assign Role (ensure team is null for global)
        if (!$admin->hasRole(RoleConstants::SADMIN)) {
            setPermissionsTeamId(null);
            $admin->assignRole(RoleConstants::SADMIN);
        }

        $this->command->info('Primary Super Admin created: superadmin@hrms.com (password: password)');
    }
}
