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
use App\Policies\AttendanceLogPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\LeavePolicy;
use App\Policies\PayrollPolicy;
use App\Policies\JobPostingPolicy;
use App\Policies\JobApplicationPolicy;
use App\Policies\PerformancePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
        // Superadmin bypasses all gates (Spatie hasSuperAdminRole checks)
        Gate::before(function ($user) {
            if ($user->hasRole('superadmin')) {
                return true;
            }
        });

        // SaaS-level access
        Gate::define('access-saas-admin', fn($user) =>
            $user->hasAnyRole(['superadmin', 'sadmin'])
        );

        Gate::define('manage-tenants', fn($user) =>
            $user->hasAnyRole(['superadmin', 'sadmin'])
        );

        Gate::define('manage-plans', fn($user) =>
            $user->hasAnyRole(['superadmin', 'sadmin'])
        );

        Gate::define('manage-billing', fn($user) =>
            $user->hasAnyRole(['superadmin', 'sadmin'])
        );

        Gate::define('view-saas-analytics', fn($user) =>
            $user->hasAnyRole(['superadmin', 'sadmin', 'smanager'])
        );

        // ── Tenant-level Gates ────────────────────────────────────────────
        Gate::define('manage-employees', fn($user) =>
            $user->hasAnyRole(['tadmin', 'tmanager'])
        );

        Gate::define('manage-payroll', fn($user) =>
            $user->hasAnyRole(['tadmin', 'tmanager'])
        );

        Gate::define('manage-leave', fn($user) =>
            $user->hasAnyRole(['tadmin', 'tmanager'])
        );

        Gate::define('view-reports', fn($user) =>
            $user->hasAnyRole(['tadmin', 'tmanager'])
        );

        Gate::define('manage-settings', fn($user) =>
            $user->hasRole('tadmin')
        );

        Gate::define('manage-recruitment', fn($user) =>
            $user->hasAnyRole(['tadmin', 'tmanager'])
        );

        // Staff can only view their own space (My Space / Profile)
        Gate::define('access-tenant', fn($user) =>
            $user->hasAnyRole(['tadmin', 'tmanager', 'tstaff'])
        );
    }
}
