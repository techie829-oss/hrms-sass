<?php

namespace App\Modules\Leave\Database\Seeders;

use App\Modules\Leave\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveSettingsSeeder extends Seeder
{
    public function run($tenantId)
    {
        $types = [
            [
                'name' => 'Casual Leave',
                'code' => 'CL',
                'max_days_per_year' => 12,
                'is_paid' => true,
                'is_carry_forward' => false,
                'description' => 'Standard casual leaves for short absences.',
            ],
            [
                'name' => 'Sick Leave',
                'code' => 'SL',
                'max_days_per_year' => 10,
                'is_paid' => true,
                'is_carry_forward' => true,
                'max_carry_forward_days' => 30,
                'description' => 'Medical leaves for health-related issues.',
            ],
            [
                'name' => 'Privilege Leave',
                'code' => 'PL',
                'max_days_per_year' => 15,
                'is_paid' => true,
                'is_carry_forward' => true,
                'max_carry_forward_days' => 45,
                'description' => 'Earned leaves for vacation and planned time-off.',
            ],
            [
                'name' => 'Leave Without Pay',
                'code' => 'LWP',
                'max_days_per_year' => 365,
                'is_paid' => false,
                'is_carry_forward' => false,
                'description' => 'Unpaid absence from work.',
            ],
            [
                'name' => 'Compensatory Off',
                'code' => 'CO',
                'max_days_per_year' => 50,
                'is_paid' => true,
                'is_carry_forward' => false,
                'description' => 'Earned leave for working on holidays or extra days.',
            ],
        ];

        foreach ($types as $type) {
            LeaveType::firstOrCreate(
                ['tenant_id' => $tenantId, 'code' => $type['code']],
                array_merge($type, ['is_active' => true])
            );
        }
    }
}
