<?php

namespace App\Providers;

use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\HR\Models\Employee;
use App\Modules\Leave\Models\LeaveRequest;
use App\Modules\Payroll\Models\PayrollRun;
use App\Modules\Payroll\Models\Payslip;
use App\Modules\Recruitment\Models\JobPosting;
use App\Modules\Recruitment\Models\JobApplication;
use App\Modules\Performance\Models\Appraisal;
use App\Modules\Performance\Models\Goal;
use App\Modules\Performance\Models\KPI;
use App\Modules\Attendance\Policies\AttendanceLogPolicy;
use App\Modules\HR\Policies\EmployeePolicy;
use App\Modules\Leave\Policies\LeavePolicy;
use App\Modules\Payroll\Policies\PayrollPolicy;
use App\Modules\Recruitment\Policies\JobPostingPolicy;
use App\Modules\Recruitment\Policies\JobApplicationPolicy;
use App\Modules\Performance\Policies\PerformancePolicy;
use App\Core\Constants\RoleConstants;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Event;
use App\Modules\HR\Events\EmployeeCreated;
use App\Modules\Leave\Listeners\AllocateLeaveBalances;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Custom SaaS Tenancy Context Singleton
        $this->app->singleton(\App\SaaS\Tenancy\TenantContext::class, function ($app) {
            return new \App\SaaS\Tenancy\TenantContext();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ── Register Modular Event Listeners ───────────────────────────────
        Event::listen(EmployeeCreated::class, AllocateLeaveBalances::class);

        // ── Register Eloquent Policies ────────────────────────────────────
        Gate::policy(Employee::class, EmployeePolicy::class);
        Gate::policy(LeaveRequest::class, LeavePolicy::class);
        Gate::policy(AttendanceLog::class, AttendanceLogPolicy::class);
        Gate::policy(PayrollRun::class, PayrollPolicy::class);
        Gate::policy(Payslip::class, PayrollPolicy::class);
        Gate::policy(JobPosting::class, JobPostingPolicy::class);
        Gate::policy(JobApplication::class, JobApplicationPolicy::class);
        Gate::policy(Appraisal::class, PerformancePolicy::class);
        Gate::policy(Goal::class, PerformancePolicy::class);
        Gate::policy(KPI::class, PerformancePolicy::class);

        // ── SaaS Internal Gates ────────────────────────────────────────────
        // SADMIN and Tenant Admins bypass all gates
        Gate::before(function ($user) {
            // Ensure team ID is scoped for Spatie when resolving gates
            if ($user->tenant_id && function_exists('setPermissionsTeamId')) {
                setPermissionsTeamId($user->tenant_id);
            }
            // Super Admin & Tenant Admin Bypass
            if ($user->hasRole(['superadmin', 'sadmin', 'tadmin', 'tmanager'])) {
                return true;
            }
        });

        // SaaS-level access
        Gate::define('access-saas-admin', fn($user) =>
            $user->hasAnyRole([RoleConstants::SADMIN, RoleConstants::SMANAGER])
        );

        Gate::define('manage-tenants', fn($user) =>
            $user->hasRole(RoleConstants::SADMIN)
        );

        Gate::define('manage-plans', fn($user) =>
            $user->hasRole(RoleConstants::SADMIN)
        );

        Gate::define('manage-billing', fn($user) =>
            $user->hasRole(RoleConstants::SADMIN)
        );

        Gate::define('view_saas_analytics', fn($user) =>
            $user->hasAnyRole([RoleConstants::SADMIN, RoleConstants::SMANAGER])
        );

        // ── Tenant-level Gates ────────────────────────────────────────────
        Gate::define('manage_employees', fn($user) =>
            $user->hasPermissionTo('view_employees')
        );

        Gate::define('manage_payroll', fn($user) =>
            $user->hasAnyPermission(['manage_payroll', 'view_payroll'])
        );



        Gate::define('view_reports', fn($user) =>
            $user->hasPermissionTo('view_reports')
        );

        Gate::define('manage_settings', fn($user) =>
            $user->hasRole(RoleConstants::TADMIN)
        );

        Gate::define('manage_recruitment', fn($user) =>
            $user->hasPermissionTo('manage_recruitment')
        );

        // Staff can access the tenant space
        Gate::define('access-tenant', fn($user) =>
            $user->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER, RoleConstants::TSTAFF])
        );
    }
}
