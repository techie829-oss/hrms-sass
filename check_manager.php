<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$role = \Spatie\Permission\Models\Role::where('name', 'Tenant Manager')->first();
if ($role) {
    echo "Tenant Manager permissions:\n";
    foreach ($role->permissions as $p) {
        echo "- " . $p->name . "\n";
    }
} else {
    echo "Tenant Manager role not found.\n";
}
