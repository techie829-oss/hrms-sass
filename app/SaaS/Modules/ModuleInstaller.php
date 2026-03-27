<?php

namespace App\SaaS\Modules;

use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ModuleInstaller
{
    /**
     * Install a module for a specific tenant.
     * Table creation is handled centrally by 'tenants:migrate'.
     */
    public static function install(string $module, Tenant $tenant): bool
    {
        // Internal state is handled by ModuleManager
        return true;
    }

    /**
     * Uninstall or rollback migrations for a module.
     */
    public static function uninstall(string $module, Tenant $tenant): bool
    {
        // Centralized rollback would be handled by a dedicated command
        return true;
    }
}
