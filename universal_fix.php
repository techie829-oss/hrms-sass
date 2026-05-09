<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$allTables = DB::select("SELECT table_schema, table_name FROM information_schema.tables WHERE table_name IN ('attendance_role_enforcements', 'attendance_employee_enforcements')");

foreach ($allTables as $t) {
    $schema = $t->table_schema;
    $table = $t->table_name;
    echo "Found $table in $schema. Checking columns...\n";
    
    $columns = DB::select("SELECT column_name FROM information_schema.columns WHERE table_schema = '$schema' AND table_name = '$table'");
    $colNames = array_column($columns, 'column_name');
    
    try {
        if ($table === 'attendance_role_enforcements') {
            if (in_array('role_name', $colNames)) {
                DB::statement("ALTER TABLE \"$schema\".$table RENAME COLUMN role_name TO role_id");
                echo "  Renamed role_name to role_id\n";
            }
        }
        
        if (in_array('checkin_required', $colNames)) {
            DB::statement("ALTER TABLE \"$schema\".$table RENAME COLUMN checkin_required TO enforce_kiosk");
            echo "  Renamed checkin_required to enforce_kiosk\n";
        }
        
        if (in_array('allow_multi_clocking', $colNames)) {
            DB::statement("ALTER TABLE \"$schema\".$table RENAME COLUMN allow_multi_clocking TO multi_clocking");
            echo "  Renamed allow_multi_clocking to multi_clocking\n";
        }
    } catch (\Exception $e) {
        echo "  Error fixing $schema.$table: " . $e->getMessage() . "\n";
    }
}
echo "Done.\n";
