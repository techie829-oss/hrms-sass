<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    echo "Starting Manual Seed Test...\n";
    
    // Test Role creation
    setPermissionsTeamId('rkservices');
    echo "Creating Role 'tadmin' for 'rkservices'...\n";
    $role = Role::firstOrCreate(['name' => 'tadmin', 'guard_name' => 'web']);
    echo "Role ID: " . $role->id . "\n";

    // Test User creation
    echo "Creating User 'admin@rkservices.com'...\n";
    $user = User::updateOrCreate(
        ['email' => 'admin@rkservices.com'],
        [
            'name' => 'Rkservices Admin',
            'password' => Hash::make('password'),
            'tenant_id' => 'rkservices',
            'email_verified_at' => now(),
        ]
    );
    echo "User ID: " . $user->id . "\n";

    // Test Role Assignment
    echo "Assigning Role...\n";
    if (!$user->hasRole('tadmin')) {
        $user->assignRole('tadmin');
    }
    echo "Role Assigned Successfully!\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "TRACE:\n" . $e->getTraceAsString() . "\n";
}
