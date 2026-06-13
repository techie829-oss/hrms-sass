<?php
$files = [
    'app/Modules/Payroll/resources/views/create.blade.php',
    'app/Modules/Payroll/resources/views/index.blade.php',
    'app/Modules/Payroll/resources/views/show.blade.php',
];

$replacements = [
    'class="card bg-base-100 shadow-sm border border-base-200"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm"',
    'class="card-body p-6 md:p-8"' => 'class="p-6 md:p-8"',
    'class="card-body p-6"' => 'class="p-6"',
    'class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden mb-12"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-12"',
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
