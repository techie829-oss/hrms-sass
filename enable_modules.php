<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tenant = \App\Models\Tenant::where('slug', 'dedicated-corp')->first();
if ($tenant) {
    $moduleManager = app(\App\SaaS\Modules\ModuleManager::class);
    $modules = ['operations', 'payroll', 'recruitment', 'performance', 'reports'];
    foreach ($modules as $mod) {
        $moduleManager->enableModule($mod, $tenant);
        echo "Enabled $mod\n";
    }
} else {
    echo "Tenant not found\n";
}
