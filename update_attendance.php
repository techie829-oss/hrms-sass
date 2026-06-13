<?php
$files = [
    'app/Modules/Attendance/resources/views/create.blade.php',
    'app/Modules/Attendance/resources/views/index.blade.php',
    'app/Modules/Attendance/resources/views/partials/calendar_view.blade.php',
    'app/Modules/Attendance/resources/views/settings.blade.php',
    'app/Modules/Attendance/resources/views/show.blade.php',
];

$replacements = [
    'class="card bg-base-100 shadow-sm border border-base-200"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm"',
    'class="card-body p-6 md:p-8"' => 'class="p-6 md:p-8"',
    'class="card-crm overflow-visible"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-visible"',
    'class="card bg-base-100 border border-base-200 border-dashed rounded-[40px] p-24 flex flex-col items-center justify-center opacity-30 grayscale"' => 'class="bg-white border border-slate-200 border-dashed rounded-xl p-24 flex flex-col items-center justify-center opacity-30 grayscale"',
    'class="card bg-base-100 border border-base-200 border-dashed rounded-[40px] p-24 flex flex-col items-center justify-center opacity-40 grayscale shadow-sm"' => 'class="bg-white border border-slate-200 border-dashed rounded-xl p-24 flex flex-col items-center justify-center opacity-40 grayscale shadow-sm"',
    'class="card bg-base-100 shadow-2xl shadow-base-content/5 border border-base-200/60 rounded-[40px] overflow-hidden"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden"',
    'class="card bg-base-100 shadow-xl shadow-base-content/5 border border-base-200/60 rounded-[32px] overflow-hidden"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden"',
    'class="card-body p-8 md:p-10 space-y-6"' => 'class="p-8 md:p-10 space-y-6"',
    'class="card-body p-8 md:p-10 space-y-8"' => 'class="p-8 md:p-10 space-y-8"',
    'class="card bg-base-100 shadow-xl shadow-base-content/5 border border-base-200/60 rounded-[32px] overflow-hidden p-8 space-y-6"' => 'class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden p-8 space-y-6"',
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
