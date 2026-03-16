<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\HR\Models\Department;
use App\Modules\HR\Models\Employee;
use App\Modules\Performance\Models\KPI;
use App\Modules\Performance\Models\Appraisal;
use App\Modules\Performance\Models\Goal;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PerformanceTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a sample Department
        $engDept = Department::firstOrCreate(['code' => 'ENG'], [
            'name' => 'Engineering',
            'description' => 'Software and Hardware engineering team',
            'is_active' => true,
        ]);

        $hrDept = Department::firstOrCreate(['code' => 'HR'], [
            'name' => 'Human Resources',
            'description' => 'People and culture',
            'is_active' => true,
        ]);

        // 2. Create sample Employees
        $johnEmail = 'john.test@example.com';
        $johnUser = User::firstOrCreate(['email' => $johnEmail], [
            'name' => 'John Doe',
            'password' => Hash::make('password'),
        ]);

        $john = Employee::firstOrCreate(['employee_id' => 'EMP_TEST_001'], [
            'user_id' => $johnUser->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => $johnEmail,
            'department_id' => $engDept->id,
            'status' => 'active',
            'date_of_joining' => now()->subYear(),
            'employment_type' => 'full_time',
        ]);

        $janeEmail = 'jane.test@example.com';
        $janeUser = User::firstOrCreate(['email' => $janeEmail], [
            'name' => 'Jane Smith',
            'password' => Hash::make('password'),
        ]);

        $jane = Employee::firstOrCreate(['employee_id' => 'EMP_TEST_002'], [
            'user_id' => $janeUser->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => $janeEmail,
            'department_id' => $hrDept->id,
            'status' => 'active',
            'date_of_joining' => now()->subYears(2),
            'employment_type' => 'full_time',
        ]);

        // 3. Create sample KPIs
        KPI::updateOrCreate(['name' => 'Code Quality Score'], [
            'description' => 'Average PR review rating',
            'department_id' => $engDept->id,
            'target_value' => 85.00,
            'unit' => 'percentage',
        ]);

        KPI::updateOrCreate(['name' => 'Server Uptime'], [
            'description' => 'Target system reliability',
            'department_id' => $engDept->id,
            'target_value' => 99.9,
            'unit' => 'percentage',
        ]);

        // 4. Create sample Appraisals
        Appraisal::updateOrCreate(['employee_id' => $john->id, 'review_period' => 'Annual Review 2026'], [
            'evaluator_id' => $jane->id,
            'score' => 92.5,
            'comments' => 'Excellent contribution to the HRMS module implementation.',
            'status' => 'completed',
        ]);

        Appraisal::updateOrCreate(['employee_id' => $john->id, 'review_period' => 'Q1 Progress Check'], [
            'evaluator_id' => $jane->id,
            'status' => 'pending',
        ]);

        // 5. Create sample Goals
        Goal::updateOrCreate(['employee_id' => $john->id, 'title' => 'Optimize API Performance'], [
            'description' => 'Reduce average response time by 30% using Redis caching.',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->addMonths(3)->endOfMonth(),
            'progress_percentage' => 45,
            'status' => 'in_progress',
        ]);

        Goal::updateOrCreate(['employee_id' => $jane->id, 'title' => 'Launch Employee Wellness Program'], [
            'description' => 'Organize and execute the Q2 wellness initiatives.',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->addMonths(2)->endOfMonth(),
            'progress_percentage' => 10,
            'status' => 'in_progress',
        ]);
    }
}
