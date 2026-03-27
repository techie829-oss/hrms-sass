<?php
/**
 * migrate_fresh_all.php
 * A utility script to perform a complete "fresh" migration for Central and Shared schemas.
 * Dedicated schemas are migrated on-demand (JIT).
 * 
 * Usage: php migrate_fresh_all.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

function log_msg($msg) {
    echo "[" . date('H:i:s') . "] " . $msg . "\n";
}

log_msg("Starting Fresh Migration Process (Central + Shared Mode)...");

// 1. Reset Central Database (Public Schema)
log_msg("Resetting Central (Public) Schema...");
// User requested standard php artisan migrate
passthru('php artisan migrate:fresh --force');

// 2. Seed Central System Data (Super Admin)
log_msg("Seeding System Data (Super Admin)...");
passthru('php artisan db:seed --class=Database\\Seeders\\DatabaseSeeder');

// 3. Seed Tenant Accounts in Central Table (Public Schema)
log_msg("Seeding Tenant Accounts (admin@rkservices.com, etc.)...");
passthru('php artisan db:seed --class=Database\\Seeders\\CentralTenantSeeder');

// 4. Drop/Cleanup Auxiliary Schemas
log_msg("Cleaning up extra schemas (shared, tenantrkservices, etc.)...");
$schemas = ['shared', 'tenantrkservices', 'tenantdedicated-corp'];
foreach ($schemas as $schema) {
    log_msg("Dropping schema: $schema");
    DB::statement("DROP SCHEMA IF EXISTS \"$schema\" CASCADE");
}

// 5. Setup Tenants in Central Database
log_msg("Setting up test tenants (rkservices, dedicated-corp)...");
Tenant::create([
    'id' => 'rkservices',
    'name' => 'RK Services (Shared)',
    'slug' => 'rkservices',
    'mode' => 'shared',
    'schema' => 'shared',
    'tenancy_db_name' => 'shared', 
    'plan_id' => 'enterprise',
    'status' => 'active',
    'email' => 'admin@rkservices.test'
]);

Tenant::create([
    'id' => 'dedicated-corp',
    'name' => 'Dedicated Corp (Dedicated)',
    'slug' => 'dedicated-corp',
    'mode' => 'dedicated',
    'schema' => 'tenantdedicated-corp',
    'tenancy_db_name' => 'tenantdedicated-corp', 
    'plan_id' => 'enterprise',
    'status' => 'active',
    'email' => 'admin@dedicated.test'
]);

// 6. Create Shared Schema manually (since we dropped it above)
log_msg("Creating Shared Schema...");
DB::statement("CREATE SCHEMA IF NOT EXISTS shared");

// 7. Run Shared Tenant Migration (Upfront)
log_msg("Migrating Shared Schema...");
passthru('php artisan tenants:migrate --tenants=rkservices --force');

log_msg("Process Complete!");
log_msg("- Shared Mode: http://rkservices.hrms.test/operations/leads (Ready)");
log_msg("- Dedicated Mode: http://dedicated-corp.hrms.test/operations/leads (Will migrate JIT on first access)");
log_msg("Accounts Seeded: admin@hrms.com, admin@rkservices.com, admin@dedicated-corp.com (password: password)");
