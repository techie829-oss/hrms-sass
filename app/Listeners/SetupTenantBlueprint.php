<?php

namespace App\Listeners;

use App\Events\TenantProvisioned;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SetupTenantBlueprint
{
    /**
     * Handle the event.
     */
    public function handle(TenantProvisioned $event): void
    {
        $tenant = $event->tenant;

        // 1. Set the team ID for Spatie Permission so roles are created for this tenant
        setPermissionsTeamId($tenant->id);

        // 2. Create standard roles for the tenant
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'tadmin', 'guard_name' => 'web', 'tenant_id' => $tenant->id]);
        $managerRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'tmanager', 'guard_name' => 'web', 'tenant_id' => $tenant->id]);
        $staffRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'tstaff', 'guard_name' => 'web', 'tenant_id' => $tenant->id]);

        // Note: Global permissions are assigned here, though tenant-specific custom permissions could also be added.
        $adminRole->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        $managerRole->givePermissionTo([
            'view dashboard', 'view employees', 'manage attendance', 'approve leave', 'view reports',
        ]);

        $staffRole->givePermissionTo([
            'view dashboard',
        ]);

        // 3. Create/Update the primary admin user for this tenant
        $adminEmail = $tenant->email;
        
        $user = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'tenant_id' => $tenant->id,
                'name' => $tenant->name . ' Admin',
                'password' => Hash::make('password'), // Ideally, trigger a password reset email
                'email_verified_at' => now(),
            ]
        );

        // 4. Assign the Tenant Admin role
        if (!$user->hasRole('super_admin')) {
            $user->syncRoles(['tadmin']);
        }

        // 5. Create a skeleton employee record for the initial admin
        \App\Modules\HR\Models\Employee::firstOrCreate(
            ['user_id' => $user->id, 'tenant_id' => $tenant->id],
            [
                'employee_id' => 'ADMIN-001',
                'first_name' => $tenant->name,
                'last_name' => 'Admin',
                'email' => $adminEmail,
                'date_of_joining' => now(),
                'employment_type' => 'full_time',
                'status' => 'active',
                'basic_salary' => 0,
            ]
        );
    }
}
