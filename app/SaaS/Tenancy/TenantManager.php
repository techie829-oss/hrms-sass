<?php

namespace App\SaaS\Tenancy;

use App\Models\Tenant;
use App\SaaS\Modules\ModuleInstaller;
use App\SaaS\Modules\ModuleManager;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantManager
{
    protected ModuleManager $moduleManager;

    public function __construct(ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    /**
     * Provision a new tenant.
     *
     * @param  array  $data  [id, name, email, domain, mode, plan_id, contact_no]
     */
    public function provision(array $data): Tenant
    {
        // 0. Pre-determine Schema and Mode
        $id = $data['id'] ?? Str::slug($data['name']);
        $mode = $data['mode'] ?? 'shared';
        $schema = ($mode === 'dedicated') ? "tenant_{$id}" : 'shared';

        // 1. Ensure Schema Exists BEFORE Tenant Record (to prevent migration errors during create)
        // Purge to ensure we start fresh
        DB::purge('pgsql');
        DB::statement("CREATE SCHEMA IF NOT EXISTS \"{$schema}\"");
        DB::purge('pgsql');

        // 2. Create Tenant Record
        $tenant = Tenant::create([
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'contact_no' => $data['contact_no'] ?? null,
            'slug' => $id,
            'mode' => $mode,
            'schema' => $schema,
            'plan_id' => $data['plan_id'] ?? 'basic',
            'status' => 'active',
        ]);

        // 3. Create Domain
        $tenant->domains()->create([
            'domain' => $data['domain'],
        ]);

        // 4. Manually Run Migrations (Since automated jobs are disabled)
            // 4. Run Migrations for this specific tenant using our custom runner
            Artisan::call('saas:migrate', [
                '--tenants' => $tenant->id,
                '--force' => true,
            ]);

        // 5. Auto-enable Core Modules
        $coreModules = ['hr', 'attendance', 'leave'];
        foreach ($coreModules as $module) {
            $this->moduleManager->enableModule($module, $tenant);
        }

        // 6. Dispatch Provisioned Event for Blueprinting
        event(new \App\Events\TenantProvisioned($tenant));

        return $tenant;
    }

    /**
     * Promote a shared tenant to dedicated (Phase 5 Placeholder).
     */
    public function promoteToDedicated(Tenant $tenant): bool
    {
        if ($tenant->mode === 'dedicated') {
            return true;
        }

        // 1. Create dedicated schema
        $newSchema = 'tenant_'.$tenant->id;
        DB::statement('CREATE SCHEMA IF NOT EXISTS '.$newSchema);

        // 2. Update tenant metadata
        $tenant->update([
            'mode' => 'dedicated',
            'schema' => $newSchema,
        ]);

        // 3. Install modules in new schema
        $modules = DB::table('tenant_modules')
            ->join('modules', 'tenant_modules.module_id', '=', 'modules.id')
            ->where('tenant_modules.tenant_id', $tenant->id)
            ->where('tenant_modules.enabled', true)
            ->pluck('modules.slug');

        foreach ($modules as $module) {
            ModuleInstaller::install($module, $tenant);
        }

        // Note: Data migration from shared to dedicated would happen here
        // SELECT * INTO tenant_acme.employees FROM shared.employees WHERE tenant_id = 'acme'

        return true;
    }
}
