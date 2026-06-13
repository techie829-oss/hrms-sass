<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tenant = \App\Models\Tenant::where('slug', 'dedicated-corp')->first();
$role = \Spatie\Permission\Models\Role::where('name', 'tmanager')->where('tenant_id', $tenant->id)->first();
if ($role) {
    echo "tmanager permissions:\n";
    foreach ($role->permissions as $p) {
        echo "- " . $p->name . "\n";
    }
} else {
    echo "tmanager role not found.\n";
}
