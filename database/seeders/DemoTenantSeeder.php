<?php

namespace Database\Seeders;

use App\Core\Constants\RoleConstants;
use App\Models\Tenant;
use App\Models\User;
use App\SaaS\Tenancy\TenantManager;
use App\SaaS\Tenancy\TenantContext;
use App\Modules\HR\Models\Employee;
use App\Modules\HR\Models\Department;
use App\Modules\HR\Models\Designation;
use App\Modules\Leave\Models\LeaveType;
use App\Modules\Leave\Models\LeaveRequest;
use App\Modules\Attendance\Models\AttendanceLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DemoTenantSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏢 Creating Demo Tenant...');

        // ── 1. Drop if already exists ──────────────────────────────────────
        $existing = Tenant::find('demo');
        if ($existing) {
            $existing->domains()->delete();
            $existing->delete();
            DB::statement('DROP SCHEMA IF EXISTS "shared" CASCADE');
            $this->command->warn('Old demo tenant removed. Re-creating...');
        }

        // ── 2. Provision Tenant via TenantManager ──────────────────────────
        /** @var TenantManager $manager */
        $manager = app(TenantManager::class);

        $tenant = $manager->provision([
            'id'         => 'demo',
            'name'       => 'Demo Company',
            'email'      => 'admin@demo.com',
            'domain'     => 'demo.hr.solidrix.com',
            'mode'       => 'shared',
            'plan_id'    => 'professional',
            'contact_no' => '+91-9800000000',
        ]);

        $this->command->info("✅ Tenant 'demo' created. ID: {$tenant->id}");

        // ── 3. Switch to Tenant Context ────────────────────────────────────
        app(TenantContext::class)->setTenant($tenant);
        setPermissionsTeamId($tenant->id);

        // ── 4. Enable All Modules for Demo ─────────────────────────────────
        $allModules = ['hr', 'attendance', 'leave', 'payroll', 'recruitment', 'projects', 'crm'];
        foreach ($allModules as $module) {
            try {
                app(\App\SaaS\Modules\ModuleManager::class)->enableModule($module, $tenant);
            } catch (\Throwable $e) {
                // Module may not exist yet — skip silently
            }
        }

        // ── 5. Departments ─────────────────────────────────────────────────
        $this->command->info('📂 Seeding Departments...');
        $departments = [
            ['name' => 'Engineering',       'code' => 'ENG',  'description' => 'Software Development & DevOps'],
            ['name' => 'Human Resources',   'code' => 'HR',   'description' => 'People Operations'],
            ['name' => 'Sales',             'code' => 'SALES','description' => 'Revenue & Business Development'],
            ['name' => 'Marketing',         'code' => 'MKT',  'description' => 'Brand & Growth Marketing'],
            ['name' => 'Finance',           'code' => 'FIN',  'description' => 'Accounts & Payroll'],
            ['name' => 'Operations',        'code' => 'OPS',  'description' => 'Internal Operations & Admin'],
        ];

        $deptMap = [];
        foreach ($departments as $dept) {
            $deptMap[$dept['code']] = Department::firstOrCreate(
                ['tenant_id' => $tenant->id, 'code' => $dept['code']],
                array_merge($dept, ['tenant_id' => $tenant->id, 'is_active' => true])
            );
        }

        // ── 6. Designations ────────────────────────────────────────────────
        $this->command->info('🏷️  Seeding Designations...');
        $designations = [
            ['title' => 'CEO',                    'department_code' => 'OPS',   'level' => 1],
            ['title' => 'CTO',                    'department_code' => 'ENG',   'level' => 1],
            ['title' => 'HR Manager',             'department_code' => 'HR',    'level' => 2],
            ['title' => 'Senior Software Engineer','department_code' => 'ENG',  'level' => 3],
            ['title' => 'Software Engineer',       'department_code' => 'ENG',  'level' => 4],
            ['title' => 'Junior Developer',        'department_code' => 'ENG',  'level' => 5],
            ['title' => 'Sales Manager',           'department_code' => 'SALES','level' => 2],
            ['title' => 'Sales Executive',         'department_code' => 'SALES','level' => 4],
            ['title' => 'Marketing Manager',       'department_code' => 'MKT',  'level' => 2],
            ['title' => 'Content Writer',          'department_code' => 'MKT',  'level' => 5],
            ['title' => 'Accountant',              'department_code' => 'FIN',  'level' => 4],
            ['title' => 'Finance Manager',         'department_code' => 'FIN',  'level' => 2],
        ];

        $desigMap = [];
        foreach ($designations as $desig) {
            $dept = $deptMap[$desig['department_code']];
            $desigMap[$desig['title']] = Designation::firstOrCreate(
                ['tenant_id' => $tenant->id, 'title' => $desig['title']],
                [
                    'tenant_id'     => $tenant->id,
                    'department_id' => $dept->id,
                    'level'         => $desig['level'],
                    'is_active'     => true,
                ]
            );
        }

        // ── 7. Leave Types ─────────────────────────────────────────────────
        $this->command->info('🏖️  Seeding Leave Types...');
        $leaveTypes = [
            ['name' => 'Casual Leave',    'code' => 'CL',  'days_allowed' => 12, 'color' => '#3B82F6'],
            ['name' => 'Sick Leave',      'code' => 'SL',  'days_allowed' => 10, 'color' => '#EF4444'],
            ['name' => 'Earned Leave',    'code' => 'EL',  'days_allowed' => 15, 'color' => '#10B981'],
            ['name' => 'Maternity Leave', 'code' => 'ML',  'days_allowed' => 90, 'color' => '#8B5CF6'],
            ['name' => 'Paternity Leave', 'code' => 'PL',  'days_allowed' => 15, 'color' => '#F59E0B'],
            ['name' => 'Loss of Pay',     'code' => 'LOP', 'days_allowed' => 0,  'color' => '#6B7280'],
        ];

        $leaveTypeMap = [];
        foreach ($leaveTypes as $lt) {
            $leaveTypeMap[$lt['code']] = LeaveType::firstOrCreate(
                ['tenant_id' => $tenant->id, 'code' => $lt['code']],
                array_merge($lt, ['tenant_id' => $tenant->id, 'is_active' => true, 'is_paid' => $lt['code'] !== 'LOP'])
            );
        }

        // ── 8. Employees ───────────────────────────────────────────────────
        $this->command->info('👥 Seeding Employees...');
        $staffRole   = Role::where('name', RoleConstants::TSTAFF)->where('tenant_id', $tenant->id)->first();
        $managerRole = Role::where('name', RoleConstants::TMANAGER)->where('tenant_id', $tenant->id)->first();

        $employees = [
            ['emp_id' => 'DEMO-001', 'first' => 'Arjun',   'last' => 'Sharma',    'email' => 'arjun@demo.com',   'dept' => 'ENG',   'desig' => 'Senior Software Engineer', 'salary' => 85000,  'role' => 'manager', 'joining' => '-2 years'],
            ['emp_id' => 'DEMO-002', 'first' => 'Priya',   'last' => 'Verma',     'email' => 'priya@demo.com',   'dept' => 'HR',    'desig' => 'HR Manager',              'salary' => 75000,  'role' => 'manager', 'joining' => '-18 months'],
            ['emp_id' => 'DEMO-003', 'first' => 'Ravi',    'last' => 'Kumar',     'email' => 'ravi@demo.com',    'dept' => 'ENG',   'desig' => 'Software Engineer',       'salary' => 60000,  'role' => 'staff',   'joining' => '-1 year'],
            ['emp_id' => 'DEMO-004', 'first' => 'Sneha',   'last' => 'Patel',     'email' => 'sneha@demo.com',   'dept' => 'SALES', 'desig' => 'Sales Executive',         'salary' => 45000,  'role' => 'staff',   'joining' => '-8 months'],
            ['emp_id' => 'DEMO-005', 'first' => 'Mohit',   'last' => 'Singh',     'email' => 'mohit@demo.com',   'dept' => 'MKT',   'desig' => 'Content Writer',          'salary' => 40000,  'role' => 'staff',   'joining' => '-6 months'],
            ['emp_id' => 'DEMO-006', 'first' => 'Ankita',  'last' => 'Joshi',     'email' => 'ankita@demo.com',  'dept' => 'FIN',   'desig' => 'Accountant',              'salary' => 55000,  'role' => 'staff',   'joining' => '-14 months'],
            ['emp_id' => 'DEMO-007', 'first' => 'Vikram',  'last' => 'Malhotra',  'email' => 'vikram@demo.com',  'dept' => 'SALES', 'desig' => 'Sales Manager',           'salary' => 80000,  'role' => 'manager', 'joining' => '-2 years'],
            ['emp_id' => 'DEMO-008', 'first' => 'Pooja',   'last' => 'Gupta',     'email' => 'pooja@demo.com',   'dept' => 'ENG',   'desig' => 'Junior Developer',        'salary' => 35000,  'role' => 'staff',   'joining' => '-4 months'],
            ['emp_id' => 'DEMO-009', 'first' => 'Rahul',   'last' => 'Nair',      'email' => 'rahul@demo.com',   'dept' => 'MKT',   'desig' => 'Marketing Manager',       'salary' => 70000,  'role' => 'manager', 'joining' => '-20 months'],
            ['emp_id' => 'DEMO-010', 'first' => 'Kavya',   'last' => 'Reddy',     'email' => 'kavya@demo.com',   'dept' => 'FIN',   'desig' => 'Finance Manager',         'salary' => 90000,  'role' => 'manager', 'joining' => '-3 years'],
        ];

        $employeeModels = [];
        foreach ($employees as $emp) {
            // Create User
            $user = User::updateOrCreate(
                ['email' => $emp['email']],
                [
                    'name'              => $emp['first'] . ' ' . $emp['last'],
                    'password'          => Hash::make('password'),
                    'tenant_id'         => $tenant->id,
                    'email_verified_at' => now(),
                ]
            );

            // Assign Role
            setPermissionsTeamId($tenant->id);
            $roleModel = ($emp['role'] === 'manager') ? $managerRole : $staffRole;
            if ($roleModel) {
                $user->syncRoles([$roleModel]);
            }

            // Create Employee Record
            $employeeModels[$emp['emp_id']] = Employee::updateOrCreate(
                ['tenant_id' => $tenant->id, 'employee_id' => $emp['emp_id']],
                [
                    'user_id'          => $user->id,
                    'tenant_id'        => $tenant->id,
                    'first_name'       => $emp['first'],
                    'last_name'        => $emp['last'],
                    'email'            => $emp['email'],
                    'department_id'    => $deptMap[$emp['dept']]->id,
                    'designation_id'   => $desigMap[$emp['desig']]->id ?? null,
                    'date_of_joining'  => now()->modify($emp['joining']),
                    'employment_type'  => 'full_time',
                    'status'           => 'active',
                    'basic_salary'     => $emp['salary'],
                    'phone'            => '+91-98' . rand(10000000, 99999999),
                    'gender'           => in_array($emp['first'], ['Priya','Sneha','Ankita','Pooja','Kavya']) ? 'female' : 'male',
                ]
            );
        }

        $this->command->info('✅ ' . count($employeeModels) . ' employees created.');

        // ── 9. Attendance Logs (Last 30 Days) ─────────────────────────────
        $this->command->info('📅 Seeding Attendance Logs (30 days)...');
        $attendanceCount = 0;

        foreach ($employeeModels as $employee) {
            for ($day = 29; $day >= 0; $day--) {
                $date = now()->subDays($day)->toDateString();
                $dayOfWeek = now()->subDays($day)->dayOfWeek;

                // Skip weekends
                if (in_array($dayOfWeek, [0, 6])) continue;

                // 90% attendance rate
                if (rand(1, 10) === 1) continue;

                $checkIn  = now()->subDays($day)->setTime(rand(8, 10), rand(0, 59), 0);
                $checkOut = $checkIn->copy()->addHours(rand(8, 10))->addMinutes(rand(0, 59));

                AttendanceLog::firstOrCreate(
                    ['tenant_id' => $tenant->id, 'employee_id' => $employee->id, 'date' => $date],
                    [
                        'tenant_id'   => $tenant->id,
                        'employee_id' => $employee->id,
                        'date'        => $date,
                        'check_in'    => $checkIn,
                        'check_out'   => $checkOut,
                        'status'      => 'present',
                        'work_hours'  => round($checkOut->diffInMinutes($checkIn) / 60, 2),
                    ]
                );
                $attendanceCount++;
            }
        }

        $this->command->info("✅ {$attendanceCount} attendance records created.");

        // ── 10. Leave Requests ─────────────────────────────────────────────
        $this->command->info('🏖️  Seeding Leave Requests...');
        $adminUser = User::where('email', 'admin@demo.com')->first();
        $leaveCount = 0;

        $leaveScenarios = [
            ['emp_id' => 'DEMO-003', 'type' => 'CL',  'days' => 2, 'offset_start' => -15, 'status' => 'approved'],
            ['emp_id' => 'DEMO-004', 'type' => 'SL',  'days' => 1, 'offset_start' => -8,  'status' => 'approved'],
            ['emp_id' => 'DEMO-005', 'type' => 'CL',  'days' => 3, 'offset_start' => 5,   'status' => 'pending'],
            ['emp_id' => 'DEMO-008', 'type' => 'EL',  'days' => 5, 'offset_start' => 10,  'status' => 'pending'],
            ['emp_id' => 'DEMO-001', 'type' => 'CL',  'days' => 2, 'offset_start' => -30, 'status' => 'approved'],
            ['emp_id' => 'DEMO-006', 'type' => 'SL',  'days' => 3, 'offset_start' => -20, 'status' => 'rejected'],
            ['emp_id' => 'DEMO-009', 'type' => 'EL',  'days' => 7, 'offset_start' => 15,  'status' => 'pending'],
        ];

        foreach ($leaveScenarios as $scenario) {
            $employee = $employeeModels[$scenario['emp_id']] ?? null;
            $leaveType = $leaveTypeMap[$scenario['type']] ?? null;
            if (!$employee || !$leaveType) continue;

            $startDate = now()->addDays($scenario['offset_start'])->toDateString();
            $endDate   = now()->addDays($scenario['offset_start'] + $scenario['days'] - 1)->toDateString();

            LeaveRequest::firstOrCreate(
                ['tenant_id' => $tenant->id, 'employee_id' => $employee->id, 'start_date' => $startDate],
                [
                    'tenant_id'     => $tenant->id,
                    'employee_id'   => $employee->id,
                    'leave_type_id' => $leaveType->id,
                    'start_date'    => $startDate,
                    'end_date'      => $endDate,
                    'days_count'    => $scenario['days'],
                    'reason'        => 'Demo leave request for ' . $leaveType->name,
                    'status'        => $scenario['status'],
                    'approved_by'   => in_array($scenario['status'], ['approved', 'rejected']) ? ($adminUser?->id) : null,
                    'approved_at'   => in_array($scenario['status'], ['approved', 'rejected']) ? now() : null,
                ]
            );
            $leaveCount++;
        }

        $this->command->info("✅ {$leaveCount} leave requests created.");

        // ── 11. Update admin user password (reset to known) ────────────────
        User::where('email', 'admin@demo.com')->update(['password' => Hash::make('password')]);

        // ── Done ───────────────────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('🎉 Demo Tenant fully seeded!');
        $this->command->table(
            ['Field', 'Value'],
            [
                ['Tenant ID',      'demo'],
                ['Domain',         'demo.hr.solidrix.com'],
                ['Admin Email',    'admin@demo.com'],
                ['Admin Password', 'password'],
                ['Employees',      count($employees)],
                ['Departments',    count($departments)],
                ['Leave Types',    count($leaveTypes)],
                ['Attendance',     $attendanceCount . ' records (30 days)'],
                ['Leave Requests', $leaveCount],
                ['All passwords',  'password'],
            ]
        );
    }
}
