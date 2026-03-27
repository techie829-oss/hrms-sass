<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SaaS\Tenancy\TenantManager;
use Illuminate\Support\Facades\DB;

class TestProvision extends Command
{
    protected $signature = 'saas:test-provision';
    protected $description = 'Test tenant provisioning';

    public function handle(TenantManager $manager)
    {
        $this->info("Starting test provisioning...");

        try {
            // Reset
            DB::statement("DROP SCHEMA IF EXISTS \"shared\" CASCADE");
            DB::statement("DROP SCHEMA IF EXISTS \"tenant_test-tenant\" CASCADE");
            DB::table('domains')->delete();
            DB::table('tenants')->delete();

            $data = [
                'id' => 'test-tenant',
                'name' => 'Test Tenant',
                'email' => 'admin@test-tenant.com',
                'domain' => 'test.hrms.test',
                'mode' => 'shared',
                'plan_id' => 'basic',
                'contact_no' => '1234567890'
            ];

            $tenant = $manager->provision($data);
            $this->info("Tenant created: " . $tenant->id);
            
            $domain = DB::table('domains')->where('tenant_id', $tenant->id)->first();
            $this->info("Domain created: " . ($domain->domain ?? 'NONE'));

            $this->info("Provisioning successful!");

        } catch (\Exception $e) {
            $this->error("Provisioning failed: " . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
