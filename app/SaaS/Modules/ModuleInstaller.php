<?php

namespace App\SaaS\Modules;

use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ModuleInstaller
{
    public static function install(string $module, Tenant $tenant): bool
    {
        $slug = strtolower($module);
        $moduleName = ucfirst($slug);
        
        // Construct the expected seeder class name
        // Example: App\Modules\Attendance\Database\Seeders\AttendanceSettingsSeeder
        $seederClass = "App\\Modules\\{$moduleName}\\Database\\Seeders\\{$moduleName}SettingsSeeder";

        if (class_exists($seederClass)) {
            // Set the TenantContext so HasDynamicSchema works
            $context = app(\App\SaaS\Tenancy\TenantContext::class);
            $previousTenant = $context->getTenant();
            $context->setTenant($tenant);

            try {
                $seeder = new $seederClass();
                if (method_exists($seeder, 'run')) {
                    $seeder->run($tenant->id);
                }
            } finally {
                // Return to previous tenant context
                $context->setTenant($previousTenant);
            }
        }

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
