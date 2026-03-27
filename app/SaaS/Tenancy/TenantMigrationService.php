<?php

namespace App\SaaS\Tenancy;

use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class TenantMigrationService
{
    /**
     * Ensure the tenant's database schema and tables are migrated.
     * 
     * @param Tenant $tenant
     * @return void
     */
    public function ensureMigrated(Tenant $tenant): void
    {
        // 1. Check if Schema exists (for PostgreSQL)
        if ($tenant->mode === 'dedicated') {
            $schemaExists = DB::selectOne("SELECT schema_name FROM information_schema.schemata WHERE schema_name = ?", [$tenant->schema]);
            
            if (!$schemaExists) {
                Log::info("JIT: Creating dedicated schema for tenant: {$tenant->id}");
                DB::statement("CREATE SCHEMA IF NOT EXISTS \"{$tenant->schema}\"");
            }
        }

        // 2. Check if main tables exist (e.g., 'departments' or 'migrations')
        // We initialize tenancy to switch the search_path first
        tenancy()->initialize($tenant);
        
        $hasMigrations = Schema::hasTable('migrations');
        $hasDepartments = Schema::hasTable('departments');

        if (!$hasMigrations || !$hasDepartments) {
            Log::info("JIT: Running migrations for tenant: {$tenant->id}");
            
            Artisan::call('tenants:migrate', [
                '--tenant' => [$tenant->id],
                '--force' => true,
            ]);

            Log::info("JIT: Migration complete for tenant: {$tenant->id}");
        }
        
        // Note: We don't necessarily end tenancy here as the middleware calling this 
        // will usually handle the lifecycle.
    }
}
