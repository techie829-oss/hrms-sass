<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tenants = DB::table('tenants')->get();

foreach ($tenants as $tenant) {
    echo "Processing tenant: {$tenant->id}\n";
    
    // Set search path to tenant schema
    DB::statement("SET search_path TO \"{$tenant->id}\"");
    
    Schema::dropIfExists('attendance_employee_enforcements');
    Schema::dropIfExists('attendance_role_enforcements');
    
    // Also remove from migrations table to allow re-running
    DB::table('migrations')
        ->where('migration', '2026_05_09_000000_create_attendance_enforcements_tables')
        ->delete();
}

echo "Cleanup complete. Now run saas:migrate.\n";
