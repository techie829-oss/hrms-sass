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
        // SADMIN bypasses all gates
        Gate::before(function ($user) {
            if ($user->hasRole(RoleConstants::SADMIN)) {
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

        Gate::define('view-saas-analytics', fn($user) =>
            $user->hasAnyRole([RoleConstants::SADMIN, RoleConstants::SMANAGER])
        );

        // ── Tenant-level Gates ────────────────────────────────────────────
        Gate::define('manage-employees', fn($user) =>
            $user->hasPermissionTo('view employees')
        );

        Gate::define('manage-payroll', fn($user) =>
            $user->hasAnyPermission(['generate payroll', 'view payroll'])
        );

        Gate::define('manage-leave', fn($user) =>
            $user->hasAnyPermission(['approve leave', 'create leave'])
        );

        Gate::define('view-reports', fn($user) =>
            $user->hasPermissionTo('view reports')
        );

        Gate::define('manage-settings', fn($user) =>
            $user->hasRole(RoleConstants::TADMIN)
        );

        Gate::define('manage-recruitment', fn($user) =>
            $user->hasPermissionTo('view dashboard')
        );

        // Staff can only view their own space (My Space / Profile)
        Gate::define('access-tenant', fn($user) =>
            $user->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER, RoleConstants::TSTAFF])
        );
    }
}
