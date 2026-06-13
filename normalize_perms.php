<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;

$permissions = Permission::all();

foreach ($permissions as $permission) {
    $oldName = $permission->name;
    // Strip, replace multi-spaces and hyphens with single underscore, lowercase
    $newName = strtolower(preg_replace('/[\s\-]+/', '_', trim($oldName)));

    if ($oldName === $newName) {
        continue;
    }

    echo "Converting '$oldName' to '$newName'...\n";

    $existing = Permission::where('name', $newName)->where('guard_name', $permission->guard_name)->first();

    if ($existing) {
        echo " -> Target already exists. Merging roles...\n";
        // Merge roles
        $roles = $permission->roles;
        foreach ($roles as $role) {
            if (!$role->hasPermissionTo($newName)) {
                $role->givePermissionTo($newName);
            }
        }
        $permission->delete();
        echo " -> Merged and deleted old permission.\n";
    } else {
        echo " -> Renaming permission.\n";
        $permission->name = $newName;
        $permission->save();
    }
}
echo "Done.\n";
