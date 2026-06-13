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
passthru('php artisan db:seed --class="Database\\\\Seeders\\\\DatabaseSeeder"');

// 3. Drop/Cleanup Auxiliary Schemas
log_msg("Cleaning up extra schemas (shared, tenantrkservices, etc.)...");
$schemas = ['shared', 'tenantrkservices', 'tenantdedicated-corp'];
foreach ($schemas as $schema) {
    log_msg("Dropping schema: $schema");
    DB::statement("DROP SCHEMA IF EXISTS \"$schema\" CASCADE");
}

// 4. Setup Tenants using TenantManager
log_msg("Setting up test tenants via TenantManager...");
$tenantManager = app(\App\SaaS\Tenancy\TenantManager::class);

$host = parse_url(config('app.url'), PHP_URL_HOST) ?? 'hrms.test';

// Create RK Services
$tenantManager->provision([
    'id' => 'rkservices',
    'name' => 'RK Services',
    'email' => 'admin@rkservices.com',
    'contact_no' => '9876543210',
    'domain' => 'rkservices.' . $host,
    'mode' => 'shared',
    'plan_id' => 'enterprise',
]);
log_msg("-> Provisioned rkservices");

// Create Dedicated Corp
$tenantManager->provision([
    'id' => 'dedicated-corp',
    'name' => 'Dedicated Corp',
    'email' => 'admin@dedicated.com',
    'contact_no' => '9876543211',
    'domain' => 'dedicated-corp.' . $host,
    'mode' => 'dedicated',
    'plan_id' => 'enterprise',
]);
log_msg("-> Provisioned dedicated-corp");

log_msg("Process Complete!");
log_msg("- Shared Mode: http://rkservices.{$host}/operations/leads (Ready)");
log_msg("- Dedicated Mode: http://dedicated-corp.{$host}/operations/leads (Ready)");
log_msg("Accounts Seeded: superadmin@hrms.com, admin@rkservices.com, admin@dedicated.com (password: password)");
