<?php

namespace App\Listeners;

use App\Events\TenantProvisioned;
use App\Core\Constants\RoleConstants;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\SaaS\Tenancy\TenantContext;
use App\Modules\HR\Models\Employee;

class SetupTenantBlueprint
{
    /**
     * Handle the event.
     */
    public function handle(TenantProvisioned $event): void
    {
        $tenant = $event->tenant;

        // Activate the tenant context for this process
        app(TenantContext::class)->setTenant($tenant);

        // 1. Set the team ID for Spatie Permission so roles are created for this tenant
        if (function_exists('setPermissionsTeamId')) {
            setPermissionsTeamId($tenant->id);
        }

        // 2. Create standard roles for the tenant
        $adminRole = Role::firstOrCreate(['name' => RoleConstants::TADMIN, 'guard_name' => 'web', 'tenant_id' => $tenant->id]);
        $managerRole = Role::firstOrCreate(['name' => RoleConstants::TMANAGER, 'guard_name' => 'web', 'tenant_id' => $tenant->id]);
        $staffRole = Role::firstOrCreate(['name' => RoleConstants::TSTAFF, 'guard_name' => 'web', 'tenant_id' => $tenant->id]);

        $managerPermissions = [
            'core_dashboard_view', 
            'hr_employee_view', 'hr_employee_create', 'hr_employee_edit',
            'attendance_log_manage', 'attendance_log_view_team',
            'leave_request_manage', 'leave_request_create', 'leave_request_cancel',
            'reports_analytics_view',
            'payroll_run_view_team',
            'timesheet_log_view_team', 'timesheet_log_manage',
            'performance_appraisal_view_team',
            'operations_lead_view', 'operations_lead_manage',
            'operations_project_view', 'operations_project_manage',
            'operations_task_view', 'operations_task_manage',
        ];

        $staffPermissions = [
            'core_dashboard_view',
            'attendance_log_view_own',
            'leave_request_view_own',
            'leave_request_create',
            'leave_request_cancel',
            'leave_holiday_view',
            'leave_compoff_view_own',
            'leave_compoff_create',
            'payroll_run_view_own',
            'timesheet_log_view_own',
            'timesheet_log_manage',
            'performance_appraisal_view_own',
            'operations_task_view',
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
        Employee::firstOrCreate(
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
