<?php
$files = [
    'app/Modules/Recruitment/resources/views/applications/show.blade.php',
    'app/Modules/Recruitment/resources/views/job_postings/create.blade.php',
    'app/Modules/Recruitment/resources/views/job_postings/edit.blade.php',
    'app/Modules/Recruitment/resources/views/job_postings/index.blade.php',
    'app/Modules/Recruitment/resources/views/job_postings/show.blade.php',
    'app/Modules/Recruitment/resources/views/public/index.blade.php',
    'app/Modules/Recruitment/resources/views/public/show.blade.php',
];

$replacements = [
    'class="card bg-base-100 shadow-sm border border-base-200"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm"',
    'class="card bg-base-100 shadow-sm border border-error/20"' => 'class="bg-white border border-red-200 rounded-xl shadow-sm"',
    'class="card bg-base-100 border border-outline-variant/30 shadow-sm hover:shadow-md hover:border-primary/50 transition-all duration-300"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md hover:border-indigo-400 transition-all duration-300"',
    'class="card bg-surface-container shadow-xl border border-outline-variant/30 sticky top-24"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm sticky top-24"',
    'class="card-body p-6 flex flex-col md:flex-row md:items-center justify-between gap-6"' => 'class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-6"',
    'class="card-body p-0"' => 'class="p-0"',
    'class="card-body p-6"' => 'class="p-6"',
    'class="card-body"' => 'class="p-6"',
    'class="card-title text-lg border-b border-base-200 pb-2 mb-4"' => 'class="text-lg font-bold text-slate-900 border-b border-slate-200 pb-2 mb-4"',
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
