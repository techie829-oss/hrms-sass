<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get all defined permissions in Constants
$reflection = new ReflectionClass(\App\Core\Constants\PermissionConstants::class);
$definedPerms = array_values($reflection->getConstants());

// Get all permissions from RoleSeeder (the DB seeders)
// We can just parse the file to see the array keys, or run it.
$seederContent = file_get_contents(__DIR__.'/database/seeders/RoleSeeder.php');
preg_match_all('/\'([a-z_]+)\'\s*=>\s*\'/', $seederContent, $seederMatches);
$seededPerms = $seederMatches[1];

// Get permissions from SetupTenantBlueprint
$blueprintContent = file_get_contents(__DIR__.'/app/Listeners/SetupTenantBlueprint.php');
preg_match_all('/\'([a-z_]+)\'/', $blueprintContent, $blueprintMatches);
// Exclude roles
$roles = ['superadmin', 'sadmin', 'smanager', 'sstaff', 'tadmin', 'tmanager', 'tstaff', 'web', 'active', 'full_time', 'pending', 'password'];
$blueprintPerms = array_diff($blueprintMatches[1], $roles);

$allKnownPerms = array_unique(array_merge($definedPerms, $seededPerms));

// Now scan the whole codebase for any string that looks like a permission:
// typically lowercase with underscores, used in can(), @can, Gate::define
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/app'));
$views = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/resources/views'));
$routes = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/routes'));

$allFiles = array_merge(iterator_to_array($files), iterator_to_array($views), iterator_to_array($routes));

$usedPerms = [];
$pattern = '/(?:can|hasPermissionTo|hasAnyPermission|authorize|Gate::define|Gate::authorize|middleware\([\'"]permission:)[^a-zA-Z_]*[\'"]([a-z_]+)[\'"]/i';

foreach ($allFiles as $file) {
    if ($file->isFile() && in_array($file->getExtension(), ['php'])) {
        $content = file_get_contents($file->getPathname());
        
        // Find normal checks
        preg_match_all($pattern, $content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $perm) {
                // Ignore policy method names like 'view', 'create', 'update', 'delete', 'restore', 'forceDelete'
                if (!in_array($perm, ['view', 'create', 'update', 'delete', 'restore', 'forceDelete', 'viewAny'])) {
                    $usedPerms[$perm][] = str_replace(__DIR__.'/', '', $file->getPathname());
                }
            }
        }
        
        // Find arrays in hasAnyPermission(['a', 'b'])
        preg_match_all('/hasAnyPermission\(\[([^\]]+)\]\)/i', $content, $arrayMatches);
        if (!empty($arrayMatches[1])) {
            foreach ($arrayMatches[1] as $arrStr) {
                preg_match_all('/[\'"]([a-z_]+)[\'"]/', $arrStr, $innerMatches);
                foreach ($innerMatches[1] as $innerPerm) {
                    if (!in_array($innerPerm, ['view', 'create', 'update', 'delete', 'restore', 'forceDelete', 'viewAny'])) {
                        $usedPerms[$innerPerm][] = str_replace(__DIR__.'/', '', $file->getPathname());
                    }
                }
            }
        }

        // Blade @can
        if (str_ends_with($file->getFilename(), '.blade.php')) {
            preg_match_all('/@can\([\'"]([a-z_]+)[\'"]\)/i', $content, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $perm) {
                    if (!in_array($perm, ['view', 'create', 'update', 'delete', 'restore', 'forceDelete', 'viewAny'])) {
                        $usedPerms[$perm][] = str_replace(__DIR__.'/', '', $file->getPathname());
                    }
                }
            }
        }
    }
}

$missingInConstants = array_diff(array_keys($usedPerms), $allKnownPerms);
$missingInSeeder = array_diff(array_keys($usedPerms), $seededPerms);
$definedButUnused = array_diff($allKnownPerms, array_keys($usedPerms));
$inBlueprintButNotSeeded = array_diff($blueprintPerms, $seededPerms);

echo "1. Permissions checked in code BUT MISSING from Constants/Seeder:\n";
foreach ($missingInConstants as $p) {
    echo "- $p (Found in: " . implode(', ', array_unique($usedPerms[$p])) . ")\n";
}

echo "\n2. Permissions in SetupTenantBlueprint BUT MISSING from RoleSeeder:\n";
print_r($inBlueprintButNotSeeded);

echo "\n3. Checking Routes for middleware:\n";
$routeContent = file_get_contents(__DIR__.'/routes/tenant.php') . file_get_contents(__DIR__.'/routes/web.php');
preg_match_all('/middleware\([\'"]permission:([^\'"]+)[\'"]\)/', $routeContent, $routeMatches);
if (!empty($routeMatches[1])) {
    foreach ($routeMatches[1] as $perms) {
        $parts = explode('|', $perms);
        foreach ($parts as $p) {
            if (!in_array($p, $allKnownPerms)) {
                echo "- Undefined in routes: $p\n";
            }
        }
    }
} else {
    echo "No permission middleware found in routes.\n";
}

