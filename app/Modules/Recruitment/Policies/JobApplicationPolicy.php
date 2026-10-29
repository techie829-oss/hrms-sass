<?php

namespace App\Modules\Recruitment\Policies;

use App\Models\User;
use App\Modules\Recruitment\Models\JobApplication;
use App\Core\Constants\PermissionConstants;

class JobApplicationPolicy
{
    private function scopeTeam(User $user): void
    {
        $tenantId = $user->tenant_id ?? (function_exists('saas_tenant') ? saas_tenant('id') : null);
        if ($tenantId && function_exists('setPermissionsTeamId')) {
            setPermissionsTeamId($tenantId);
        }
    }

    private function hasPermission(User $user, string $permission): bool
    {
        try {
            return $user->hasPermissionTo($permission);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function viewAny(User $user): bool
    {
        $this->scopeTeam($user);

        return $user->hasRole(['tadmin', 'tmanager', 'superadmin', 'sadmin']) ||
               $this->hasPermission($user, PermissionConstants::VIEW_RECRUITMENT) ||
               $this->hasPermission($user, PermissionConstants::MANAGE_RECRUITMENT);
    }

    public function view(User $user, JobApplication $application): bool
    {
        $userTenantId = $user->tenant_id ?? (function_exists('saas_tenant') ? saas_tenant('id') : null);
        if ($application->tenant_id && $userTenantId && (string) $application->tenant_id !== (string) $userTenantId) {
            return false;
        }

        $this->scopeTeam($user);

        return $user->hasRole(['tadmin', 'tmanager', 'superadmin', 'sadmin']) ||
               $this->hasPermission($user, PermissionConstants::VIEW_RECRUITMENT) ||
               $this->hasPermission($user, PermissionConstants::MANAGE_RECRUITMENT);
    }

    public function create(User $user): bool
    {
        $this->scopeTeam($user);

        return $user->hasRole(['tadmin', 'tmanager', 'superadmin', 'sadmin']) ||
               $this->hasPermission($user, PermissionConstants::MANAGE_RECRUITMENT);
    }

    public function update(User $user, JobApplication $application): bool
    {
        $userTenantId = $user->tenant_id ?? (function_exists('saas_tenant') ? saas_tenant('id') : null);
        if ($application->tenant_id && $userTenantId && (string) $application->tenant_id !== (string) $userTenantId) {
            return false;
        }

        $this->scopeTeam($user);

        return $user->hasRole(['tadmin', 'tmanager', 'superadmin', 'sadmin']) ||
               $this->hasPermission($user, PermissionConstants::MANAGE_RECRUITMENT);
    }

    public function delete(User $user, JobApplication $application): bool
    {
        $userTenantId = $user->tenant_id ?? (function_exists('saas_tenant') ? saas_tenant('id') : null);
        if ($application->tenant_id && $userTenantId && (string) $application->tenant_id !== (string) $userTenantId) {
            return false;
        }

        $this->scopeTeam($user);

        return $user->hasRole(['tadmin', 'tmanager', 'superadmin', 'sadmin']) ||
               $this->hasPermission($user, PermissionConstants::MANAGE_RECRUITMENT);
    }
}
