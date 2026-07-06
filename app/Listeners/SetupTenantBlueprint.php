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

        $managerPermissions = [
            'view_dashboard', 
            'view_employees', 'create_employees', 'edit_employees',
            'manage_attendance', 'view_attendance',
            'manage_leave', 'create_leave', 'cancel_leave',
            'view_reports',
            'view_payroll',
            'view_timesheet', 'manage_timesheet',
            'view_performance',
            'view_leads', 'manage_leads',
            'view_projects', 'manage_projects',
            'view_tasks', 'manage_tasks',
        ];

        $staffPermissions = [
            'view_dashboard',
            'view_own_attendance',
            'view_own_leave',
            'create_leave',
            'cancel_leave',
            'view_holidays',
            'view_own_comp_off',
            'create_comp_off',
            'view_own_payroll',
            'view_own_timesheet',
            'manage_timesheet',
            'view_own_performance',
            'view_tasks',
        ];

        $allPermissions = array_unique(array_merge($managerPermissions, $staffPermissions));
        foreach ($allPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Note: tadmin gets ALL available permissions. Using syncPermissions ensures
        // it is always up-to-date even if called when some permissions don't exist yet.
        $adminRole->syncPermissions(Permission::all());

        $managerRole->syncPermissions($managerPermissions);
        $staffRole->syncPermissions($staffPermissions);

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

        // 4. Assign the Tenant Admin role (pass the model instance, not the string, so Spatie picks the correct tenant_id)
        if (!$user->hasRole(RoleConstants::SADMIN)) {
            $user->syncRoles([$adminRole]);
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
