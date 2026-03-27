<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TenantsMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saas:migrate {--tenants= : Comma separated IDs of tenants to migrate} {--force : Force the migration} {--seed : Seed after migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations for all or specific tenants safely using PostgreSQL schemas.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantsList = $this->option('tenants');
        $tenants = $tenantsList 
            ? Tenant::whereIn('id', explode(',', $tenantsList))->get()
            : Tenant::all();

        if ($tenants->isEmpty()) {
            $this->info('No tenants found to migrate.');
            return;
        }

        foreach ($tenants as $tenant) {
            $this->info("Migrating tenant: {$tenant->id} (Schema: {$tenant->schema})");

            if (!$tenant->schema) {
                $this->error("Tenant {$tenant->id} does not have a schema assigned.");
                continue;
            }

            // Absolute Isolation: Update the connection configuration for the migration process
            config(['database.connections.pgsql.search_path' => $tenant->schema]);
            DB::purge('pgsql');
            DB::reconnect('pgsql');

            try {
                // 1. Run module-specific migrations (base tables for modules)
                $modulePaths = glob(base_path('app/Modules/*/database/migrations'));
                
                // Ensure HR module migrations run first (others depend on employees)
                usort($modulePaths, function ($a, $b) {
                    if (str_contains($a, '/HR/')) return -1;
                    if (str_contains($b, '/HR/')) return 1;
                    return 0;
                });

                foreach ($modulePaths as $path) {
                    $this->comment("  -> Migrating Module Path: " . basename(dirname(dirname($path))));
                    Artisan::call('migrate', [
                        '--database' => 'pgsql',
                        '--path'     => str_replace(base_path().'/', '', $path),
                        '--force'    => $this->option('force'),
                    ], $this->output);
                }

                // 2. Run standard Laravel migration on the default pgsql connection
                // but focused on the tenant-specific paths (leads, contacts etc)
                Artisan::call('migrate', [
                    '--database' => 'pgsql',
                    '--path'     => 'database/migrations/tenant',
                    '--force'    => $this->option('force'),
                ], $this->output);

                if ($this->option('seed')) {
                    $this->info("  -> Seeding tenant: {$tenant->id}");
                    // Custom Seeding logic would go here
                }

            } catch (\Exception $e) {
                $this->error("  -> Migration failed for {$tenant->id}: " . $e->getMessage());
            } finally {
                // Return to public schema
                config(['database.connections.pgsql.search_path' => 'public']);
                DB::purge('pgsql');
            }
        }

        $this->info('Tenant migrations completed.');
    }
}
