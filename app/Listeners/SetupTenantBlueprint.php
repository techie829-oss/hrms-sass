<?php

namespace App\Listeners;

use App\Events\TenantProvisioned;
use App\Core\Constants\RoleConstants;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class SetupTenantBlueprint
{
    /**
     * Handle the event.
     */
    public function handle(TenantProvisioned $event): void
    {
        $tenant = $event->tenant;

        // Activate the tenant context for this process
        app(\App\SaaS\Tenancy\TenantContext::class)->setTenant($tenant);

        // 1. Set the team ID for Spatie Permission so roles are created for this tenant
        if (function_exists('setPermissionsTeamId')) {
            setPermissionsTeamId($tenant->id);
        }

        // 2. Create standard roles for the tenant
        $adminRole = Role::firstOrCreate(['name' => RoleConstants::TADMIN, 'guard_name' => 'web', 'tenant_id' => $tenant->id]);
        $managerRole = Role::firstOrCreate(['name' => RoleConstants::TMANAGER, 'guard_name' => 'web', 'tenant_id' => $tenant->id]);
        $staffRole = Role::firstOrCreate(['name' => RoleConstants::TSTAFF, 'guard_name' => 'web', 'tenant_id' => $tenant->id]);

        // Note: Global permissions are inherited, but assigned to tenant roles here.
        $adminRole->givePermissionTo(Permission::all());

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
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 4. Assign the Tenant Admin role
        if (!$user->hasRole(RoleConstants::SADMIN)) {
            $user->syncRoles([RoleConstants::TADMIN]);
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
