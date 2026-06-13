<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Update role_has_permissions
DB::statement("
    UPDATE role_has_permissions
    SET permission_id = (
        SELECT p2.id FROM permissions p2
        WHERE p2.name = REPLACE(permissions.name, '-', '_')
        AND p2.guard_name = permissions.guard_name
        LIMIT 1
    )
    FROM permissions
    WHERE role_has_permissions.permission_id = permissions.id
    AND permissions.name LIKE '%-%'
    AND EXISTS (
        SELECT 1 FROM permissions p3
        WHERE p3.name = REPLACE(permissions.name, '-', '_')
        AND p3.guard_name = permissions.guard_name
    );
");

// Update model_has_permissions
DB::statement("
    UPDATE model_has_permissions
    SET permission_id = (
        SELECT p2.id FROM permissions p2
        WHERE p2.name = REPLACE(permissions.name, '-', '_')
        AND p2.guard_name = permissions.guard_name
        LIMIT 1
    )
    FROM permissions
    WHERE model_has_permissions.permission_id = permissions.id
    AND permissions.name LIKE '%-%'
    AND EXISTS (
        SELECT 1 FROM permissions p3
        WHERE p3.name = REPLACE(permissions.name, '-', '_')
        AND p3.guard_name = permissions.guard_name
    );
");

// Delete permissions with hyphens if an underscored version exists
DB::statement("
    DELETE FROM permissions 
    WHERE name LIKE '%-%' 
    AND EXISTS (
        SELECT 1 FROM permissions p3
        WHERE p3.name = REPLACE(permissions.name, '-', '_')
        AND p3.guard_name = permissions.guard_name
    )
");

// Replace remaining hyphens with underscores
DB::statement("UPDATE permissions SET name = REPLACE(name, '-', '_') WHERE name LIKE '%-%'");

app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
echo "Fixed DB permissions.\n";
