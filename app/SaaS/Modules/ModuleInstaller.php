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
     * This runs the module-specific migrations within the correct PostgreSQL schema.
     */
    public static function install(string $module, Tenant $tenant): bool
    {
        // 1. Unified migration path for all tenants (Shared or Dedicated)
        $path = 'app/Modules/'.ucfirst($module).'/database/migrations';
        $migrationPath = base_path($path);

        // 2. Check if directory exists
        if (! File::isDirectory($migrationPath)) {
            return false;
        }

        // 3. Initialize tenancy
        tenancy()->initialize($tenant);

        // 4. Set PostgreSQL search_path based on mode
        $currentSchema = ($tenant->mode === 'dedicated')
            ? ($tenant->schema ?? 'tenant_'.$tenant->id)
            : 'shared';

        DB::statement("SET search_path TO {$currentSchema}, public");

        // 5. Run migrations for this unified path
        Artisan::call('migrate', [
            '--path' => $path,
            '--force' => true,
            '--database' => 'tenant',
        ]);

        return true;
    }

    /**
     * Uninstall or rollback migrations for a module.
     */
    public static function uninstall(string $module, Tenant $tenant): bool
    {
        $path = 'app/Modules/'.ucfirst($module).'/database/migrations';
        $migrationPath = base_path($path);

        if (! File::isDirectory($migrationPath)) {
            return false;
        }

        tenancy()->initialize($tenant);

        $currentSchema = ($tenant->mode === 'dedicated')
            ? ($tenant->schema ?? 'tenant_'.$tenant->id)
            : 'shared';

        DB::statement("SET search_path TO {$currentSchema}, public");

        Artisan::call('migrate:rollback', [
            '--path' => $path,
            '--force' => true,
            '--database' => 'tenant',
        ]);

        return true;
    }
}
