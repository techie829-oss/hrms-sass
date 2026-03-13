<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Roles are seeded
        $this->call(RoleSeeder::class);

        // 2. Create Super Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@hrms.com'],
            [
                'name' => 'System Architect',
                'password' => Hash::make('password'), // Change this in production
                'email_verified_at' => now(),
            ]
        );

        // 3. Assign Super Admin Role
        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }

        $this->command->info('Super Admin created successfully: admin@hrms.com / password');
    }
}
