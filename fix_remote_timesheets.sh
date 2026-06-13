ssh -o StrictHostKeyChecking=no root@72.62.247.252 "cat << 'INNEREOF' > /opt/hrms/fix_timesheets.php
<?php
require __DIR__.'/vendor/autoload.php';
\$app = require_once __DIR__.'/bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

\$timesheets = DB::table('shared.timesheets')->whereNull('start_time')->get();
foreach (\$timesheets as \$ts) {
    \$hours = \$ts->hours;
    \$start = Carbon::createFromTime(10, 0);
    \$end = \$start->copy()->addMinutes(\$hours * 60);
    
    DB::table('shared.timesheets')->where('id', \$ts->id)->update([
        'start_time' => \$start->format('H:i:s'),
        'end_time'   => \$end->format('H:i:s')
    ]);
}
echo 'Fixed ' . \$timesheets->count() . ' timesheets.\n';
INNEREOF
docker exec hrms_app php fix_timesheets.php
"
