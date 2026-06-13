<?php
$files = [
    'app/Modules/Leave/resources/views/holidays/index.blade.php',
    'app/Modules/Leave/resources/views/types/index.blade.php',
];

$replacements = [
    'class="card-crm p-6 group relative hover:border-primary/30"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 group relative hover:border-indigo-300"',
    'class="card-crm hover:border-primary/30 group flex flex-col h-full"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-indigo-300 group flex flex-col h-full"',
    'class="card-body p-8 flex-1"' => 'class="p-8 flex-1"',
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    file_put_contents($file, $content);
}
echo "Done\n";
