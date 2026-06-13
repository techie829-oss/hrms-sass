<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/app/Modules');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile() && in_array($file->getExtension(), ['php'])) {
        $content = file_get_contents($file->getPathname());
        $originalContent = $content;
        
        // Match @can('...'), can('...'), hasPermissionTo('...')
        // We will replace hyphens with underscores inside these specific function calls.
        
        $content = preg_replace_callback(
            '/(?:@can|can|hasPermissionTo)\(\s*[\'"]([a-zA-Z0-9\-]+)[\'"]\s*\)/',
            function ($matches) {
                $perm = str_replace('-', '_', $matches[1]);
                // Reconstruct the full match string with the new permission
                return str_replace($matches[1], $perm, $matches[0]);
            },
            $content
        );

        if ($content !== $originalContent) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
echo "Done replacing hyphens in permissions.\n";
