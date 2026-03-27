<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

try {
    echo "1. Creating 'shared' schema...\n";
    DB::statement('CREATE SCHEMA IF NOT EXISTS shared;');

    echo "2. Provisioning 'shared_v1' tenant...\n";
    $tenant = Tenant::updateOrCreate(
        ['id' => 'shared_v1'],
        [
            'name' => 'Shared Initial',
            'email' => 'shared@init.com',
            'mode' => 'shared',
            'schema' => 'shared'
        ]
    );

    echo "3. Mapping domain 'shared.hrms.test'...\n";
    $tenant->domains()->updateOrCreate(
        ['domain' => 'shared.hrms.test'],
        ['domain' => 'shared.hrms.test']
    );

    echo "System Initialization: SUCCESS\n";
} catch (Exception $e) {
    echo "System Initialization: FAILED\n";
    echo $e->getMessage() . "\n";
    exit(1);
}
