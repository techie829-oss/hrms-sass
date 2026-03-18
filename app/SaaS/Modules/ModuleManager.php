<?php

namespace App\SaaS\Modules;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ModuleManager
{
    /**
     * Available modules in the system.
     */
    protected array $modules = [];

    public function __construct()
    {
        $this->discoverModules();
    }

    /**
     * Auto-discover modules in the app/Modules directory.
     */
    protected function discoverModules(): void
    {
        $modulePath = app_path('Modules');
        if (! File::isDirectory($modulePath)) {
            return;
        }

        $directories = File::directories($modulePath);

        foreach ($directories as $directory) {
            $name = basename($directory);
            $this->modules[strtolower($name)] = [
                'name' => $name,
                'path' => $directory,
                'free' => in_array(strtolower($name), ['hr', 'attendance', 'leave']),
                'icon' => $this->getModuleIcon(strtolower($name)),
            ];
        }
    }

    /**
     * Get default icon for a module.
     */
    protected function getModuleIcon(string $slug): string
    {
        return match ($slug) {
            'hr' => 'group',
            'attendance' => 'calendar_today',
            'leave' => 'event_busy',
            'payroll' => 'payments',
            'performance' => 'trending_up',
            'recruitment' => 'work',
            'reports' => 'insert_chart',
            'operations' => 'account_tree',
            default => 'extension',
        };
    }

    /**
     * Get all available modules.
     */
    public function getAvailableModules(): array
    {
        return $this->modules;
    }

    /**
     * Check if a tenant has access to a module.
     */
    public function tenantHasAccess(string $module, string $tenantId): bool
    {
        $slug = strtolower($module);

        if (! isset($this->modules[$slug])) {
            return false;
        }

        // Check the database for tenant enablement
        return DB::table('tenant_modules')
            ->join('modules', 'tenant_modules.module_id', '=', 'modules.id')
            ->where('tenant_modules.tenant_id', $tenantId)
            ->where('modules.slug', $slug)
            ->where('tenant_modules.enabled', true)
            ->exists();
    }

    /**
     * Enable a module for a tenant (including running migrations).
     */
    public function enableModule(string $module, Tenant $tenant): bool
    {
        $slug = strtolower($module);

        if (! isset($this->modules[$slug])) {
            return false;
        }

        // 1. Ensure module exists in the global `modules` table
        $moduleRecord = DB::table('modules')
            ->where('slug', $slug)
            ->first();

        if (! $moduleRecord) {
            $moduleId = DB::table('modules')->insertGetId([
                'name' => $this->modules[$slug]['name'],
                'slug' => $slug,
                'is_free' => $this->modules[$slug]['free'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $moduleRecord = (object) ['id' => $moduleId];
        }

        // 2. Track enablement in the `tenant_modules` table
        DB::table('tenant_modules')->updateOrInsert(
            ['tenant_id' => $tenant->id, 'module_id' => $moduleRecord->id],
            ['enabled' => true, 'installed_at' => now(), 'updated_at' => now()]
        );

        // 3. Use ModuleInstaller to run migrations in tenant context
        return ModuleInstaller::install($slug, $tenant);
    }

    /**
     * Disable a module for a tenant.
     */
    public function disableModule(string $module, Tenant $tenant): bool
    {
        $slug = strtolower($module);

        $moduleRecord = DB::table('modules')->where('slug', $slug)->first();

        if (! $moduleRecord) {
            return false;
        }

        DB::table('tenant_modules')->updateOrInsert(
            ['tenant_id' => $tenant->id, 'module_id' => $moduleRecord->id],
            ['enabled' => false, 'updated_at' => now()]
        );

        return true;
    }
}
