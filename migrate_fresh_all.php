<?php
/**
 * migrate_fresh_all.php
 * A utility script to perform a complete "fresh" migration for both Central and Tenant schemas.
 * 
 * Usage: php migrate_fresh_all.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant;

function log_msg($msg) {
    echo "[" . date('H:i:s') . "] " . $msg . "\n";
}

log_msg("Starting Fresh Migration Process...");

// 1. Reset Central Database (Public Schema)
log_msg("Resetting Central (Public) Schema...");
passthru('php artisan migrate:fresh --path=database/migrations/system --force');

// 2. Drop Required Auxiliary Schemas
log_msg("Cleaning up extra schemas (shared, tenantrkservices, etc.)...");
$schemas = ['shared', 'tenantrkservices', 'tenantdedicated-corp'];
foreach ($schemas as $schema) {
    log_msg("Dropping schema: $schema");
    DB::statement("DROP SCHEMA IF EXISTS \"$schema\" CASCADE");
}

// 3. Setup Tenants in Central Database
log_msg("Setting up test tenants (rkservices, dedicated-corp)...");
Tenant::create([
    'id' => 'rkservices',
    'name' => 'RK Services (Shared)',
    'slug' => 'rkservices',
    'mode' => 'shared',
    'schema' => 'shared',
    'tenancy_db_name' => 'shared', // Force it to use the 'shared' schema
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
    'tenancy_db_name' => 'tenantdedicated-corp', // Isolate it to its own schema
    'plan_id' => 'enterprise',
    'status' => 'active',
    'email' => 'admin@dedicated.test'
]);

// 4. Run Tenant Migrations
log_msg("Running migrations for all tenants...");
// This will automatically pick up paths from config/tenancy.php (including modules)
passthru('php artisan tenants:migrate --force');

log_msg("Fresh Migration Complete!");
log_msg("Ready for Testing:");
log_msg("- Shared Mode: http://rkservices.hrms.test/operations/leads");
log_msg("- Dedicated Mode: http://dedicated-corp.hrms.test/operations/leads");
