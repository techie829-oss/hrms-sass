<?php
$files = [
    'app/Modules/HR/resources/views/departments/index.blade.php',
    'app/Modules/HR/resources/views/designations/index.blade.php',
    'app/Modules/HR/resources/views/employees/create.blade.php',
    'app/Modules/HR/resources/views/employees/edit.blade.php',
    'app/Modules/HR/resources/views/employees/index.blade.php',
    'app/Modules/HR/resources/views/employees/show.blade.php',
];

$replacements = [
    'class="card bg-surface-container-lowest shadow-sm border border-outline-variant/10 hover:border-primary/30 transition-all flex flex-col justify-between min-h-[160px] rounded-xl overflow-hidden"' => 'class="bg-white shadow-sm border border-slate-200 hover:border-indigo-300 transition-all flex flex-col justify-between min-h-[160px] rounded-xl overflow-hidden"',
    'class="card-body p-5"' => 'class="p-5"',
    'class="card bg-surface-container-lowest shadow-sm border border-outline-variant/10 hover:border-primary/30 transition-all flex flex-col justify-between min-h-[200px] rounded-2xl overflow-hidden group/card relative"' => 'class="bg-white shadow-sm border border-slate-200 hover:border-indigo-300 transition-all flex flex-col justify-between min-h-[200px] rounded-xl overflow-hidden group/card relative"',
    'class="card-body p-6"' => 'class="p-6"',
    'class="card bg-base-100 shadow-sm border border-base-200"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm"',
    'class="card-body"' => 'class="p-6"',
    'class="card-title text-lg border-b border-base-200 pb-2 mb-4"' => 'class="text-lg font-bold text-slate-900 border-b border-slate-200 pb-2 mb-4"',
    'class="card bg-base-100 shadow-sm border border-base-200 text-center overflow-hidden"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm text-center overflow-hidden"',
    'class="card-body -mt-12 relative"' => 'class="p-6 -mt-12 relative"',
    'class="card-body p-0"' => 'class="p-0"',
    'class="card bg-base-100 shadow-sm border border-base-200 p-4"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm p-4"',
    'class="card bg-base-100 shadow-sm border border-base-200 p-6 relative group overflow-hidden"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 relative group overflow-hidden"',
    'class="card-crm"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm"',
    'class="card-crm-header"' => 'class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50 rounded-t-xl"',
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
