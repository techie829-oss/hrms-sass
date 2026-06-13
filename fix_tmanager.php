<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$perms = [
    'view_leads', 'manage_leads',
    'view_projects', 'manage_projects',
    'view_tasks', 'manage_tasks',
    'view_timesheet', 'manage_timesheet'
];

$roles = \Spatie\Permission\Models\Role::whereIn('name', ['tadmin', 'tmanager'])->get();

foreach ($roles as $r) {
    if ($r->name == 'tadmin') {
        $r->givePermissionTo(\Spatie\Permission\Models\Permission::all());
    } elseif ($r->name == 'tmanager') {
        foreach ($perms as $p) {
            $permModel = \Spatie\Permission\Models\Permission::where('name', $p)->first();
            if ($permModel && !$r->hasPermissionTo($p)) {
                $r->givePermissionTo($p);
            }
        }
    }
}
echo "Done fixing tmanager permissions.\n";
