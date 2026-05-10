<?php

namespace App\Modules\Attendance\Database\Seeders;

use App\Modules\Attendance\Models\AttendancePolicy;
use App\Modules\Attendance\Models\AttendanceShift;
use Illuminate\Database\Seeder;

class AttendanceSettingsSeeder extends Seeder
{
    public function run($tenantId)
    {
        // 1. Create Default Attendance Policy
        AttendancePolicy::firstOrCreate(
            ['tenant_id' => $tenantId, 'name' => 'Default Organization Policy'],
            [
                'description' => 'Standard organization-wide attendance rules.',
                'late_mark_after_minutes' => 15,
                'early_leave_before_minutes' => 30,
                'min_hours_full_day' => 8,
                'min_hours_half_day' => 4,
                'is_active' => true,
                'is_default' => true,
                'is_manual_enabled' => true,
                'enforce_clockin' => false,
                'allow_multi_clocking' => true,
                'default_start_time' => '09:00:00',
                'default_end_time' => '18:00:00',
            ]
        );

        // 2. Create General Shift
        AttendanceShift::firstOrCreate(
            ['tenant_id' => $tenantId, 'name' => 'General Shift'],
            [
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'grace_minutes' => 15,
                'half_day_hours' => 4,
                'min_hours_full_day' => 8,
                'is_active' => true,
                'is_default' => true,
            ]
        );
    }
}
