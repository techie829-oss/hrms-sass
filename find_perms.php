<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app/'));
$views = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views/'));
$routes = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('routes/'));

$allFiles = array_merge(iterator_to_array($files), iterator_to_array($views), iterator_to_array($routes));

$permissionsInCode = [];
$pattern = '/(can|hasPermissionTo|hasAnyPermission|Gate::define|Gate::authorize|middleware\([\'"]permission:)([\'"])([a-z_]+)\2/i';

foreach ($allFiles as $file) {
    if ($file->isFile() && in_array($file->getExtension(), ['php'])) {
        $content = file_get_contents($file->getPathname());
        preg_match_all($pattern, $content, $matches);
        
        if (!empty($matches[3])) {
            foreach ($matches[3] as $perm) {
                $permissionsInCode[$perm] = true;
            }
        }
        
        // Also check for array of permissions in hasAnyPermission(['a', 'b'])
        preg_match_all('/hasAnyPermission\(\[([^\]]+)\]\)/i', $content, $arrayMatches);
        if (!empty($arrayMatches[1])) {
            foreach ($arrayMatches[1] as $arrStr) {
                preg_match_all('/[\'"]([a-z_]+)[\'"]/', $arrStr, $innerMatches);
                foreach ($innerMatches[1] as $innerPerm) {
                    $permissionsInCode[$innerPerm] = true;
                }
            }
        }
    }
}

// Special check for Blade @can
foreach (iterator_to_array($views) as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        preg_match_all('/@can\([\'"]([a-z_]+)[\'"]\)/i', $content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $perm) {
                $permissionsInCode[$perm] = true;
            }
        }
    }
}

$permissionsInCode = array_keys($permissionsInCode);
sort($permissionsInCode);
echo "Permissions used in code:\n";
print_r($permissionsInCode);
