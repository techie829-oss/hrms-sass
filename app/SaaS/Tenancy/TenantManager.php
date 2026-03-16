<?php

namespace App\SaaS\Tenancy;

use App\Models\Tenant;
use App\SaaS\Modules\ModuleInstaller;
use App\SaaS\Modules\ModuleManager;
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
        // 1. Create Tenant Record
        $tenant = Tenant::create([
            'id' => $data['id'] ?? Str::slug($data['name']),
            'name' => $data['name'],
            'email' => $data['email'],
            'contact_no' => $data['contact_no'] ?? null,
            'slug' => $data['id'] ?? Str::slug($data['name']),
            'mode' => $data['mode'] ?? 'shared',
            'schema' => ($data['mode'] === 'dedicated') ? 'tenant_'.($data['id'] ?? Str::slug($data['name'])) : 'shared',
            'plan_id' => $data['plan_id'] ?? 'basic',
            'status' => 'active',
        ]);

        // 2. Create Domain
        $tenant->domains()->create([
            'domain' => $data['domain'],
        ]);

        // 3. Initialize and Setup Schema if Dedicated
        if ($tenant->mode === 'dedicated') {
            DB::statement('CREATE SCHEMA IF NOT EXISTS '.$tenant->schema);
        }

        // 4. Auto-enable Core Modules
        $coreModules = ['hr', 'attendance', 'leave'];
        foreach ($coreModules as $module) {
            $this->moduleManager->enableModule($module, $tenant);
        }

        // 5. Dispatch Provisioned Event for Blueprinting
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
