<?php

namespace App\Modules\HR\Database\Seeders;

use App\Modules\HR\Models\Department;
use App\Modules\HR\Models\Designation;
use Illuminate\Database\Seeder;

class HRSettingsSeeder extends Seeder
{
    public function run($tenantId)
    {
        // 1. Seed Default Departments
        $departments = [
            ['name' => 'Human Resources', 'code' => 'HR', 'description' => 'Management of employee lifecycle and relations.'],
            ['name' => 'Information Technology', 'code' => 'IT', 'description' => 'Technical support and software development.'],
            ['name' => 'Sales & Marketing', 'code' => 'SM', 'description' => 'Revenue generation and brand management.'],
            ['name' => 'Finance', 'code' => 'FIN', 'description' => 'Accounting and financial planning.'],
            ['name' => 'Operations', 'code' => 'OPS', 'description' => 'Core business execution and logistics.'],
        ];

        $deptIds = [];
        foreach ($departments as $dept) {
            $createdDept = Department::firstOrCreate(
                ['tenant_id' => $tenantId, 'code' => $dept['code']],
                array_merge($dept, ['is_active' => true])
            );
            $deptIds[$dept['code']] = $createdDept->id;
        }

        // 2. Seed Default Designations
        $designations = [
            // HR Designations
            ['name' => 'HR Manager', 'code' => 'HRM', 'dept' => 'HR'],
            ['name' => 'HR Executive', 'code' => 'HRE', 'dept' => 'HR'],
            
            // IT Designations
            ['name' => 'Software Engineer', 'code' => 'SWE', 'dept' => 'IT'],
            ['name' => 'System Administrator', 'code' => 'SYS', 'dept' => 'IT'],
            
            // Sales Designations
            ['name' => 'Sales Manager', 'code' => 'SMG', 'dept' => 'SM'],
            ['name' => 'Sales Associate', 'code' => 'SA', 'dept' => 'SM'],
            
            // Finance Designations
            ['name' => 'Accountant', 'code' => 'ACC', 'dept' => 'FIN'],
            
            // Operations Designations
            ['name' => 'Operations Manager', 'code' => 'OPM', 'dept' => 'OPS'],
        ];

        foreach ($designations as $desig) {
            Designation::firstOrCreate(
                ['tenant_id' => $tenantId, 'code' => $desig['code']],
                [
                    'name' => $desig['name'],
                    'department_id' => $deptIds[$desig['dept']] ?? null,
                    'is_active' => true
                ]
            );
        }
    }
}
