<?php
$files = [
    'app/Modules/Operations/resources/views/clients/index.blade.php',
    'app/Modules/Operations/resources/views/contacts/create.blade.php',
    'app/Modules/Operations/resources/views/contacts/edit.blade.php',
    'app/Modules/Operations/resources/views/contacts/index.blade.php',
    'app/Modules/Operations/resources/views/leads/create.blade.php',
    'app/Modules/Operations/resources/views/leads/edit.blade.php',
    'app/Modules/Operations/resources/views/leads/index.blade.php',
    'app/Modules/Operations/resources/views/leads/show.blade.php',
    'app/Modules/Operations/resources/views/projects/create.blade.php',
    'app/Modules/Operations/resources/views/projects/index.blade.php',
    'app/Modules/Operations/resources/views/projects/show.blade.php',
    'app/Modules/Operations/resources/views/tasks/create.blade.php',
    'app/Modules/Operations/resources/views/tasks/edit.blade.php',
    'app/Modules/Operations/resources/views/tasks/index.blade.php',
    'app/Modules/Operations/resources/views/timesheets/index.blade.php',
];

$replacements = [
    'class="card bg-base-100 shadow-xl border border-base-200"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm"',
    'class="card bg-base-100 shadow-sm border border-base-200"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm"',
    'class="card bg-base-100 shadow-xl border border-base-200 hover:border-primary/30 transition-all"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-indigo-300 transition-all"',
    'class="card bg-primary text-primary-content shadow-lg"' => 'class="bg-indigo-600 text-white rounded-xl shadow-sm"',
    'class="card bg-base-100 shadow-sm border border-base-200 cursor-pointer hover:border-primary/30 transition-colors"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-indigo-300 transition-colors"',
    'class="card-crm"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm"',
    'class="card-actions justify-end mt-6 pt-4 border-t border-base-200"' => 'class="flex justify-end mt-6 pt-4 border-t border-slate-200"',
    'class="card-actions justify-between mt-6"' => 'class="flex justify-between mt-6"',
    'class="card-actions justify-end mt-10"' => 'class="flex justify-end mt-10"',
    'class="card-actions justify-end mt-4"' => 'class="flex justify-end mt-4"',
    'class="card-actions justify-center mt-2"' => 'class="flex justify-center mt-2"',
    'class="card-actions justify-end mt-6"' => 'class="flex justify-end mt-6"',
    'class="card-actions justify-end"' => 'class="flex justify-end"',
    'class="card-body p-8"' => 'class="p-8"',
    'class="card-body p-0"' => 'class="p-0"',
    'class="card-body p-4"' => 'class="p-4"',
    'class="card-body pt-6 px-6 pb-2"' => 'class="pt-6 px-6 pb-2"',
    'class="card-body"' => 'class="p-6"',
    'class="card-title text-base"' => 'class="text-base font-bold text-slate-900"',
    'class="card-title text-lg"' => 'class="text-lg font-bold text-slate-900"',
    'class="card-title text-xl mb-4"' => 'class="text-xl font-bold text-slate-900 mb-4"',
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
