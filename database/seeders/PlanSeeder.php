<?php

namespace Database\Seeders;

use App\SaaS\Billing\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = config('billing.plans', []);

        foreach ($plans as $slug => $data) {
            Plan::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'price_monthly' => $data['price_monthly'],
                    'price_yearly' => $data['price_yearly'],
                    'max_employees' => $data['max_employees'],
                    'max_modules' => $data['max_modules'],
                    'is_active' => true,
                ]
            );
        }
    }
}
