<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$schemas = ['rkservices', 'tenant_rschitra'];

foreach ($schemas as $schema) {
    echo "Cleaning schema: $schema\n";
    try {
        // Drop tables
        DB::statement("DROP TABLE IF EXISTS \"$schema\".attendance_employee_enforcements CASCADE");
        DB::statement("DROP TABLE IF EXISTS \"$schema\".attendance_role_enforcements CASCADE");
        
        // Try to clear migration history
        try {
            DB::statement("DELETE FROM \"$schema\".migrations WHERE migration LIKE '%create_attendance_enforcements_tables%'");
            echo "  Migration history cleared.\n";
        } catch (\Exception $e) {
            echo "  Migration table not found in schema, skipping history clear.\n";
        }
    } catch (\Exception $e) {
        echo "  Error cleaning $schema: " . $e->getMessage() . "\n";
    }
}
echo "Done.\n";
