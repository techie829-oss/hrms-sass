<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$perms = [
    'view leads', 'manage leads',
    'view projects', 'manage projects',
    'view tasks', 'manage tasks',
    'view timesheet', 'manage timesheet'
];

foreach ($perms as $p) {
    \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
}

// sync to existing tadmin and tmanager roles across all tenants
$roles = \Spatie\Permission\Models\Role::all();
foreach ($roles as $r) {
    if ($r->name == 'Tenant Admin') {
        $r->givePermissionTo(\Spatie\Permission\Models\Permission::all());
    } elseif ($r->name == 'Tenant Manager') {
        $r->givePermissionTo($perms);
    }
}
echo "Done\n";
