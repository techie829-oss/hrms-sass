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
use Carbon\Carbon;

class DemoTenantSeeder extends Seeder
{
    private string $tenantId = 'demo';
    private Tenant $tenant;
    private array $deptMap    = [];
    private array $desigMap   = [];
    private array $empMap     = [];   // emp_id => Employee model
    private array $userMap    = [];   // emp_id => User model
    private array $leaveMap   = [];   // code   => LeaveType model
    private array $projectMap = [];   // slug   => project id
    private array $clientMap  = [];

    // ───────────────────────────────────────────────────────────────────────
    public function run(): void
    {
        $this->command->info('🏢 Creating Demo Tenant...');

        // 1. Cleanup ────────────────────────────────────────────────────────
        $existing = Tenant::withTrashed()->find($this->tenantId);
        if ($existing) {
            $existing->domains()->forceDelete();
            $existing->forceDelete();
            DB::statement('DROP SCHEMA IF EXISTS "shared" CASCADE');
            DB::statement('CREATE SCHEMA IF NOT EXISTS "shared"');
            $this->command->warn('Old demo tenant removed. Re-creating...');
        }

        // 2. Provision ──────────────────────────────────────────────────────
        $this->tenant = app(TenantManager::class)->provision([
            'id'         => $this->tenantId,
            'name'       => 'Demo Company',
            'email'      => 'admin@demo.com',
            'domain'     => 'demo.hr.solidrix.com',
            'mode'       => 'shared',
            'plan_id'    => 'professional',
            'contact_no' => '+91-9800000000',
        ]);

        app(TenantContext::class)->setTenant($this->tenant);
        setPermissionsTeamId($this->tenant->id);
        $this->command->info("✅ Tenant '{$this->tenantId}' provisioned.");

        // Enable all modules
        $allModules = ['hr', 'attendance', 'leave', 'payroll', 'recruitment', 'projects', 'crm'];
        foreach ($allModules as $m) {
            try { app(\App\SaaS\Modules\ModuleManager::class)->enableModule($m, $this->tenant); }
            catch (\Throwable $e) {}
        }

        // 3. Run seeders ────────────────────────────────────────────────────
        $this->seedDepartments();
        $this->seedDesignations();
        $this->seedShifts();
        $this->seedAttendancePolicy();
        $this->seedHolidays();
        $this->seedLeaveTypes();
        $this->seedEmployees();
        $this->seedLeaveBalances();
        $this->seedSalaryComponents();
        $this->seedSalaryStructures();
        $this->seedAttendanceLogs();
        $this->seedLeaveRequests();
        $this->seedPayrollRun();
        $this->seedClients();
        $this->seedLeads();
        $this->seedProjects();
        $this->seedTasksAndTimesheets();
        $this->seedGoalsAndKPIs();
        $this->seedAppraisals();

        // Update admin password to known
        User::where('email', 'admin@demo.com')->update(['password' => Hash::make('password')]);

        // Summary ───────────────────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('🎉 Demo Tenant fully seeded!');
        $this->command->table(['Field', 'Value'], [
            ['Domain',          'demo.hr.solidrix.com'],
            ['Admin',           'admin@demo.com / password'],
            ['Manager',         'arjun@demo.com / password'],
            ['Employee',        'ravi@demo.com / password'],
            ['Employees',       count($this->empMap)],
            ['Departments',     count($this->deptMap)],
            ['Shifts',          '3 (Morning / General / Night)'],
            ['Holidays',        '14'],
            ['Leave Types',     count($this->leaveMap)],
            ['Payroll Run',     'May 2025 — Processed'],
            ['Projects',        count($this->projectMap)],
            ['Clients',         count($this->clientMap)],
        ]);
    }

    // ── DEPARTMENTS ─────────────────────────────────────────────────────────
    private function seedDepartments(): void
    {
        $this->command->info('📂 Departments...');
        foreach ([
            ['name' => 'Engineering',     'code' => 'ENG',  'description' => 'Software Development & DevOps'],
            ['name' => 'Human Resources', 'code' => 'HR',   'description' => 'People Operations & Compliance'],
            ['name' => 'Sales',           'code' => 'SALES','description' => 'Revenue & Business Development'],
            ['name' => 'Marketing',       'code' => 'MKT',  'description' => 'Brand & Growth Marketing'],
            ['name' => 'Finance',         'code' => 'FIN',  'description' => 'Accounts, Payroll & Budgets'],
            ['name' => 'Operations',      'code' => 'OPS',  'description' => 'Internal Operations & Admin'],
        ] as $d) {
            $this->deptMap[$d['code']] = Department::firstOrCreate(
                ['tenant_id' => $this->tenant->id, 'code' => $d['code']],
                array_merge($d, ['tenant_id' => $this->tenant->id, 'is_active' => true])
            );
        }
    }

    // ── DESIGNATIONS ─────────────────────────────────────────────────────────
    private function seedDesignations(): void
    {
        $this->command->info('🏷️  Designations...');
        foreach ([
            ['name' => 'CEO',                     'code' => 'CEO', 'dept' => 'OPS'],
            ['name' => 'CTO',                     'code' => 'CTO', 'dept' => 'ENG'],
            ['name' => 'HR Manager',              'code' => 'HRM', 'dept' => 'HR'],
            ['name' => 'Senior Software Engineer','code' => 'SSE', 'dept' => 'ENG'],
            ['name' => 'Software Engineer',       'code' => 'SE',  'dept' => 'ENG'],
            ['name' => 'Junior Developer',        'code' => 'JD',  'dept' => 'ENG'],
            ['name' => 'Sales Manager',           'code' => 'SM',  'dept' => 'SALES'],
            ['name' => 'Sales Executive',         'code' => 'SEX', 'dept' => 'SALES'],
            ['name' => 'Marketing Manager',       'code' => 'MM',  'dept' => 'MKT'],
            ['name' => 'Content Writer',          'code' => 'CW',  'dept' => 'MKT'],
            ['name' => 'Finance Manager',         'code' => 'FM',  'dept' => 'FIN'],
            ['name' => 'Accountant',              'code' => 'ACC', 'dept' => 'FIN'],
        ] as $d) {
            $this->desigMap[$d['name']] = Designation::firstOrCreate(
                ['tenant_id' => $this->tenant->id, 'code' => $d['code']],
                [
                    'tenant_id'     => $this->tenant->id,
                    'name'          => $d['name'],
                    'code'          => $d['code'],
                    'department_id' => $this->deptMap[$d['dept']]->id,
                    'is_active'     => true,
                ]
            );
        }
    }

    // ── ATTENDANCE SHIFTS ──────────────────────────────────────────────────
    private function seedShifts(): void
    {
        $this->command->info('⏰ Attendance Shifts...');
        $tid = $this->tenant->id;
        $shifts = [
            [
                'tenant_id'          => $tid,
                'name'               => 'General Shift',
                'start_time'         => '09:00:00',
                'end_time'           => '18:00:00',
                'grace_minutes'      => 15,
                'half_day_hours'     => 4,
                'min_hours_full_day' => 8,
                'weekly_offs'        => json_encode(['Saturday', 'Sunday']),
                'is_overnight'       => false,
                'is_default'         => true,
                'is_active'          => true,
            ],
            [
                'tenant_id'          => $tid,
                'name'               => 'Morning Shift',
                'start_time'         => '07:00:00',
                'end_time'           => '15:00:00',
                'grace_minutes'      => 10,
                'half_day_hours'     => 4,
                'min_hours_full_day' => 7.5,
                'weekly_offs'        => json_encode(['Sunday']),
                'is_overnight'       => false,
                'is_default'         => false,
                'is_active'          => true,
            ],
            [
                'tenant_id'          => $tid,
                'name'               => 'Night Shift',
                'start_time'         => '22:00:00',
                'end_time'           => '06:00:00',
                'grace_minutes'      => 15,
                'half_day_hours'     => 4,
                'min_hours_full_day' => 7.5,
                'weekly_offs'        => json_encode(['Sunday']),
                'is_overnight'       => true,
                'is_default'         => false,
                'is_active'          => true,
            ],
        ];
        foreach ($shifts as $s) {
            DB::table('shared.attendance_shifts')->insertOrIgnore($s + ['created_at' => now(), 'updated_at' => now()]);
        }
    }

    // ── ATTENDANCE POLICY ─────────────────────────────────────────────────
    private function seedAttendancePolicy(): void
    {
        $this->command->info('📋 Attendance Policy...');
        DB::table('shared.attendance_policies')->insertOrIgnore([
            'tenant_id'                  => $this->tenant->id,
            'name'                       => 'Standard Policy',
            'description'                => 'Default attendance policy for all employees',
            'late_mark_after_minutes'    => 15,
            'early_leave_before_minutes' => 30,
            'min_hours_full_day'         => 8.0,
            'min_hours_half_day'         => 4.0,
            'auto_deduct_leave'          => true,
            'max_late_allowed_per_month' => 3,
            'enforce_clockin'            => true,
            'allow_multi_clocking'       => false,
            'is_kiosk_enabled'           => true,
            'kiosk_require_photo'        => false,
            'kiosk_require_location'     => false,
            'is_mobile_enabled'          => true,
            'is_manual_enabled'          => true,
            'is_default'                 => true,
            'is_active'                  => true,
            'auto_checkout'              => true,
            'auto_checkout_time'         => '23:59:00',
            'default_start_time'         => '09:00:00',
            'default_end_time'           => '18:00:00',
            'created_at'                 => now(),
            'updated_at'                 => now(),
        ]);
    }

    // ── HOLIDAYS ──────────────────────────────────────────────────────────
    private function seedHolidays(): void
    {
        $this->command->info('🎉 Holidays...');
        $year = now()->year;
        $holidays = [
            ["Republic Day",          "{$year}-01-26", false],
            ["Holi",                  "{$year}-03-25", false],
            ["Good Friday",           "{$year}-04-18", true],
            ["Ram Navami",            "{$year}-04-06", true],
            ["Maharashtra Day",       "{$year}-05-01", false],
            ["Eid ul-Fitr",          "{$year}-03-31", false],
            ["Independence Day",      "{$year}-08-15", false],
            ["Janmashtami",           "{$year}-08-16", true],
            ["Gandhi Jayanti",        "{$year}-10-02", false],
            ["Dussehra",              "{$year}-10-02", false],
            ["Diwali",                "{$year}-10-20", false],
            ["Diwali (Balipratipada","{$year}-10-21", false],
            ["Guru Nanak Jayanti",    "{$year}-11-05", true],
            ["Christmas Day",         "{$year}-12-25", false],
        ];
        foreach ($holidays as [$name, $date, $optional]) {
            DB::table('shared.holidays')->insertOrIgnore([
                'tenant_id'   => $this->tenant->id,
                'name'        => $name,
                'date'        => $date,
                'is_optional' => $optional,
                'description' => $optional ? 'Optional Holiday' : 'Mandatory Holiday',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }

    // ── LEAVE TYPES ───────────────────────────────────────────────────────
    private function seedLeaveTypes(): void
    {
        $this->command->info('🏖️  Leave Types...');
        foreach ([
            ['name' => 'Casual Leave',    'code' => 'CL',  'max_days' => 12, 'paid' => true,  'carry' => false],
            ['name' => 'Sick Leave',      'code' => 'SL',  'max_days' => 10, 'paid' => true,  'carry' => false],
            ['name' => 'Earned Leave',    'code' => 'EL',  'max_days' => 15, 'paid' => true,  'carry' => true],
            ['name' => 'Maternity Leave', 'code' => 'ML',  'max_days' => 90, 'paid' => true,  'carry' => false],
            ['name' => 'Paternity Leave', 'code' => 'PL',  'max_days' => 15, 'paid' => true,  'carry' => false],
            ['name' => 'Loss of Pay',     'code' => 'LOP', 'max_days' => 0,  'paid' => false, 'carry' => false],
            ['name' => 'Comp Off',        'code' => 'CO',  'max_days' => 5,  'paid' => true,  'carry' => false],
            ['name' => 'Bereavement',     'code' => 'BL',  'max_days' => 5,  'paid' => true,  'carry' => false],
        ] as $lt) {
            $this->leaveMap[$lt['code']] = LeaveType::firstOrCreate(
                ['tenant_id' => $this->tenant->id, 'code' => $lt['code']],
                [
                    'tenant_id'          => $this->tenant->id,
                    'name'               => $lt['name'],
                    'code'               => $lt['code'],
                    'max_days_per_year'  => $lt['max_days'],
                    'is_paid'            => $lt['paid'],
                    'is_carry_forward'   => $lt['carry'],
                    'requires_approval'  => true,
                    'is_active'          => true,
                ]
            );
        }
    }

    // ── EMPLOYEES ─────────────────────────────────────────────────────────
    private function seedEmployees(): void
    {
        $this->command->info('👥 Employees...');
        $staffRole   = Role::where('name', RoleConstants::TSTAFF)->where('tenant_id', $this->tenant->id)->first();
        $managerRole = Role::where('name', RoleConstants::TMANAGER)->where('tenant_id', $this->tenant->id)->first();
        $adminRole   = Role::where('name', RoleConstants::TADMIN)->where('tenant_id', $this->tenant->id)->first();

        $employees = [
            // emp_id, first, last, email, dept, desig, salary, role, joining, gender, phone
            ['DEMO-001','Arjun',   'Sharma',   'arjun@demo.com',   'ENG',  'Senior Software Engineer', 85000, 'manager','-2 years',  'male',  '+91-9810001001'],
            ['DEMO-002','Priya',   'Verma',    'priya@demo.com',   'HR',   'HR Manager',               75000, 'manager','-18 months','female','+91-9810001002'],
            ['DEMO-003','Ravi',    'Kumar',    'ravi@demo.com',    'ENG',  'Software Engineer',        60000, 'staff',  '-1 year',   'male',  '+91-9810001003'],
            ['DEMO-004','Sneha',   'Patel',    'sneha@demo.com',   'SALES','Sales Executive',          45000, 'staff',  '-8 months', 'female','+91-9810001004'],
            ['DEMO-005','Mohit',   'Singh',    'mohit@demo.com',   'MKT',  'Content Writer',           40000, 'staff',  '-6 months', 'male',  '+91-9810001005'],
            ['DEMO-006','Ankita',  'Joshi',    'ankita@demo.com',  'FIN',  'Accountant',               55000, 'staff',  '-14 months','female','+91-9810001006'],
            ['DEMO-007','Vikram',  'Malhotra', 'vikram@demo.com',  'SALES','Sales Manager',            80000, 'manager','-2 years',  'male',  '+91-9810001007'],
            ['DEMO-008','Pooja',   'Gupta',    'pooja@demo.com',   'ENG',  'Junior Developer',         35000, 'staff',  '-4 months', 'female','+91-9810001008'],
            ['DEMO-009','Rahul',   'Nair',     'rahul@demo.com',   'MKT',  'Marketing Manager',        70000, 'manager','-20 months','male',  '+91-9810001009'],
            ['DEMO-010','Kavya',   'Reddy',    'kavya@demo.com',   'FIN',  'Finance Manager',          90000, 'manager','-3 years',  'female','+91-9810001010'],
            ['DEMO-011','Amit',    'Tiwari',   'amit@demo.com',    'ENG',  'Software Engineer',        58000, 'staff',  '-9 months', 'male',  '+91-9810001011'],
            ['DEMO-012','Deepika', 'Roy',      'deepika@demo.com', 'HR',   'HR Manager',               65000, 'manager','-15 months','female','+91-9810001012'],
        ];

        foreach ($employees as [$empId, $first, $last, $email, $dept, $desig, $salary, $roleKey, $joining, $gender, $phone]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'              => "$first $last",
                    'password'          => Hash::make('password'),
                    'tenant_id'         => $this->tenant->id,
                    'email_verified_at' => now(),
                ]
            );

            setPermissionsTeamId($this->tenant->id);
            $roleModel = match($roleKey) {
                'admin'   => $adminRole,
                'manager' => $managerRole,
                default   => $staffRole,
            };
            if ($roleModel) $user->syncRoles([$roleModel]);

            $this->userMap[$empId] = $user;
            $this->empMap[$empId]  = Employee::updateOrCreate(
                ['tenant_id' => $this->tenant->id, 'employee_id' => $empId],
                [
                    'user_id'         => $user->id,
                    'tenant_id'       => $this->tenant->id,
                    'first_name'      => $first,
                    'last_name'       => $last,
                    'email'           => $email,
                    'phone'           => $phone,
                    'department_id'   => $this->deptMap[$dept]->id,
                    'designation_id'  => $this->desigMap[$desig]->id ?? null,
                    'date_of_joining' => now()->modify($joining),
                    'employment_type' => 'full_time',
                    'status'          => 'active',
                    'basic_salary'    => $salary,
                    'gender'          => $gender,
                ]
            );
        }
        $this->command->info('  ✅ ' . count($this->empMap) . ' employees created.');
    }

    // ── LEAVE BALANCES ────────────────────────────────────────────────────
    private function seedLeaveBalances(): void
    {
        $this->command->info('📊 Leave Balances...');
        $year = now()->year;
        foreach ($this->empMap as $emp) {
            foreach ($this->leaveMap as $code => $leaveType) {
                if ($leaveType->max_days_per_year == 0) continue;
                $used    = rand(0, min(5, $leaveType->max_days_per_year));
                $balance = $leaveType->max_days_per_year - $used;
                DB::table('shared.leave_balances')->insertOrIgnore([
                    'tenant_id'       => $this->tenant->id,
                    'employee_id'     => $emp->id,
                    'leave_type_id'   => $leaveType->id,
                    'year'            => $year,
                    'total_allocated' => $leaveType->max_days_per_year,
                    'total_used'      => $used,
                    'total_pending'   => 0,
                    'carried_forward' => 0,
                    'balance'         => $balance,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }
    }

    // ── SALARY COMPONENTS ─────────────────────────────────────────────────
    private function seedSalaryComponents(): void
    {
        $this->command->info('💰 Salary Components...');
        $components = [
            // name, code, type, calc_type, default_amount, pct_of, pct_base, taxable, mandatory
            ['Basic Salary',          'BASIC', 'earning',   'fixed',      0,    null,    null,    true,  true],
            ['House Rent Allowance',  'HRA',   'earning',   'percentage', 0,    'BASIC', 40,      true,  true],
            ['Special Allowance',     'SA',    'earning',   'fixed',      5000, null,    null,    true,  false],
            ['Transport Allowance',   'TA',    'earning',   'fixed',      2000, null,    null,    false, false],
            ['Medical Allowance',     'MA',    'earning',   'fixed',      1250, null,    null,    false, false],
            ['Performance Bonus',     'PB',    'earning',   'fixed',      0,    null,    null,    true,  false],
            ['Provident Fund (EE)',   'PF',    'deduction', 'percentage', 0,    'BASIC', 12,      false, true],
            ['Professional Tax',      'PT',    'deduction', 'fixed',      200,  null,    null,    false, true],
            ['Income Tax (TDS)',       'TDS',   'deduction', 'fixed',      0,    null,    null,    false, false],
            ['ESI (Employee)',        'ESI',   'deduction', 'percentage', 0,    'BASIC', 0.75,    false, false],
        ];
        foreach ($components as [$name, $code, $type, $calcType, $amount, $pctOf, $pctBase, $taxable, $mandatory]) {
            DB::table('shared.salary_components')->insertOrIgnore([
                'tenant_id'        => $this->tenant->id,
                'name'             => $name,
                'code'             => $code,
                'type'             => $type,
                'calculation_type' => $calcType,
                'default_amount'   => $amount,
                'percentage_of'    => $pctOf,
                'percentage_base'  => $pctBase,
                'is_taxable'       => $taxable,
                'is_mandatory'     => $mandatory,
                'is_active'        => true,
                'display_order'    => array_search([$name, $code, $type, $calcType, $amount, $pctOf, $pctBase, $taxable, $mandatory], $components),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }

    // ── SALARY STRUCTURES ─────────────────────────────────────────────────
    private function seedSalaryStructures(): void
    {
        $this->command->info('📄 Salary Structures...');
        foreach ($this->empMap as $empId => $emp) {
            $basic    = $emp->basic_salary;
            $hra      = round($basic * 0.4);
            $ta       = 2000;
            $ma       = 1250;
            $sa       = 5000;
            $gross    = $basic + $hra + $ta + $ma + $sa;
            $pf       = round($basic * 0.12);
            $pt       = 200;
            $net      = $gross - $pf - $pt;
            $earnings   = json_encode(['BASIC' => $basic, 'HRA' => $hra, 'TA' => $ta, 'MA' => $ma, 'SA' => $sa]);
            $deductions = json_encode(['PF' => $pf, 'PT' => $pt]);

            DB::table('shared.salary_structures')->insertOrIgnore([
                'tenant_id'    => $this->tenant->id,
                'employee_id'  => $emp->id,
                'ctc'          => $gross + $pf,
                'gross_salary' => $gross,
                'net_salary'   => $net,
                'earnings'     => $earnings,
                'deductions'   => $deductions,
                'effective_from'=> now()->subYear()->startOfYear()->toDateString(),
                'effective_to' => null,
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }

    // ── ATTENDANCE LOGS ───────────────────────────────────────────────────
    private function seedAttendanceLogs(): void
    {
        $this->command->info('📅 Attendance Logs (60 days)...');
        $count = 0;
        foreach ($this->empMap as $emp) {
            for ($day = 59; $day >= 0; $day--) {
                $date      = now()->subDays($day)->toDateString();
                $dayOfWeek = now()->subDays($day)->dayOfWeek;
                if (in_array($dayOfWeek, [0, 6])) continue; // weekend
                if (rand(1, 10) === 1) continue;             // 90% rate

                $checkIn  = Carbon::parse($date . ' 09:' . str_pad(rand(0, 25), 2, '0', STR_PAD_LEFT) . ':00');
                $checkOut = $checkIn->copy()->addHours(rand(8, 10))->addMinutes(rand(0, 59));

                DB::table('shared.attendance_logs')->insertOrIgnore([
                    'tenant_id'    => $this->tenant->id,
                    'employee_id'  => $emp->id,
                    'date'         => $date,
                    'check_in'     => $checkIn,
                    'check_out'    => $checkOut,
                    'worked_hours' => round($checkOut->diffInMinutes($checkIn) / 60, 2),
                    'status'       => 'present',
                    'is_late'      => $checkIn->format('H:i') > '09:15',
                    'late_minutes' => max(0, $checkIn->diffInMinutes(Carbon::parse($date . ' 09:15:00'), false)),
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
                $count++;
            }
        }
        $this->command->info("  ✅ {$count} records.");
    }

    // ── LEAVE REQUESTS ────────────────────────────────────────────────────
    private function seedLeaveRequests(): void
    {
        $this->command->info('🏖️  Leave Requests...');
        $adminId = $this->userMap['DEMO-002']->id ?? null; // Priya HR Manager
        $scenarios = [
            ['DEMO-003', 'CL',  2, -15, 'approved'],
            ['DEMO-004', 'SL',  1, -8,  'approved'],
            ['DEMO-005', 'CL',  3,  5,  'pending'],
            ['DEMO-008', 'EL',  5,  10, 'pending'],
            ['DEMO-001', 'CL',  2, -30, 'approved'],
            ['DEMO-006', 'SL',  3, -20, 'rejected'],
            ['DEMO-009', 'EL',  7,  15, 'pending'],
            ['DEMO-011', 'CL',  1, -5,  'approved'],
            ['DEMO-012', 'SL',  2, -10, 'approved'],
        ];
        foreach ($scenarios as [$empId, $typeCode, $days, $offsetStart, $status]) {
            $emp       = $this->empMap[$empId] ?? null;
            $leaveType = $this->leaveMap[$typeCode] ?? null;
            if (!$emp || !$leaveType) continue;
            $start = now()->addDays($offsetStart)->toDateString();
            $end   = now()->addDays($offsetStart + $days - 1)->toDateString();
            DB::table('shared.leave_requests')->insertOrIgnore([
                'tenant_id'     => $this->tenant->id,
                'employee_id'   => $emp->id,
                'leave_type_id' => $leaveType->id,
                'start_date'    => $start,
                'end_date'      => $end,
                'total_days'    => $days,
                'reason'        => "Demo: {$leaveType->name} request",
                'status'        => $status,
                'approved_by'   => in_array($status, ['approved','rejected']) ? $adminId : null,
                'approved_at'   => in_array($status, ['approved','rejected']) ? now()->subDays(2) : null,
                'created_at'    => now()->addDays($offsetStart - 2),
                'updated_at'    => now(),
            ]);
        }
    }

    // ── PAYROLL RUN ───────────────────────────────────────────────────────
    private function seedPayrollRun(): void
    {
        $this->command->info('💸 Payroll Run...');
        $month = now()->subMonth()->month;
        $year  = now()->subMonth()->year;
        $adminUser = User::where('email', 'admin@demo.com')->first();

        $totalGross = 0; $totalDed = 0; $totalNet = 0;
        foreach ($this->empMap as $emp) {
            $basic   = $emp->basic_salary;
            $gross   = $basic + round($basic * 0.4) + 2000 + 1250 + 5000;
            $ded     = round($basic * 0.12) + 200;
            $net     = $gross - $ded;
            $totalGross += $gross; $totalDed += $ded; $totalNet += $net;
        }

        $runId = DB::table('shared.payroll_runs')->insertGetId([
            'tenant_id'        => $this->tenant->id,
            'title'            => Carbon::createFromDate($year, $month, 1)->format('F Y') . ' Payroll',
            'month'            => $month,
            'year'             => $year,
            'pay_date'         => Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString(),
            'status'           => 'processed',
            'total_employees'  => count($this->empMap),
            'total_gross'      => $totalGross,
            'total_deductions' => $totalDed,
            'total_net'        => $totalNet,
            'processed_by'     => $adminUser?->id,
            'processed_at'     => now()->subMonth()->endOfMonth(),
            'notes'            => 'Auto-generated demo payroll run.',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        // Payslips
        foreach ($this->empMap as $i => $emp) {
            $basic    = $emp->basic_salary;
            $hra      = round($basic * 0.4);
            $ta = 2000; $ma = 1250; $sa = 5000;
            $gross    = $basic + $hra + $ta + $ma + $sa;
            $pf       = round($basic * 0.12);
            $pt       = 200;
            $net      = $gross - $pf - $pt;
            DB::table('shared.payslips')->insertOrIgnore([
                'tenant_id'           => $this->tenant->id,
                'payroll_run_id'      => $runId,
                'employee_id'         => $emp->id,
                'payslip_number'      => 'PAY-' . $year . $month . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'month'               => $month,
                'year'                => $year,
                'working_days'        => 26,
                'present_days'        => rand(22, 26),
                'absent_days'         => rand(0, 2),
                'leave_days'          => rand(0, 2),
                'holidays'            => 2,
                'basic_salary'        => $basic,
                'gross_earnings'      => $gross,
                'total_deductions'    => $pf + $pt,
                'net_salary'          => $net,
                'earnings_breakdown'  => json_encode(['Basic' => $basic, 'HRA' => $hra, 'TA' => $ta, 'MA' => $ma, 'SA' => $sa]),
                'deductions_breakdown'=> json_encode(['PF' => $pf, 'PT' => $pt]),
                'status'              => 'paid',
                'payment_date'        => Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString(),
                'payment_mode'        => 'bank_transfer',
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }
        $this->command->info('  ✅ Payroll run + ' . count($this->empMap) . ' payslips created.');
    }

    // ── CLIENTS ───────────────────────────────────────────────────────────
    private function seedClients(): void
    {
        $this->command->info('🤝 Clients...');
        $clients = [
            ['Infosys Limited',     'contact@infosys.com',   '+91-80-2852-0261', 'Infosys Ltd',    'active'],
            ['TCS Enterprise',      'vendor@tcs.com',        '+91-22-6778-9999', 'Tata Consultancy','active'],
            ['Wipro Technologies',  'biz@wipro.com',         '+91-80-2844-0011', 'Wipro Ltd',      'active'],
            ['HCL Tech Solutions',  'partnerships@hcl.com',  '+91-120-432-1234', 'HCL Technologies','inactive'],
            ['Zomato Pvt Ltd',      'enterprise@zomato.com', '+91-11-4000-5000', 'Zomato',         'active'],
        ];
        foreach ($clients as [$name, $email, $phone, $company, $status]) {
            $id = DB::table('shared.clients')->insertGetId([
                'tenant_id'  => $this->tenant->id,
                'name'       => $name,
                'email'      => $email,
                'phone'      => $phone,
                'company'    => $company,
                'address'    => 'India',
                'status'     => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->clientMap[$company] = $id;
        }
    }

    // ── LEADS ─────────────────────────────────────────────────────────────
    private function seedLeads(): void
    {
        $this->command->info('📈 Leads...');
        $salesEmp = $this->empMap['DEMO-004']->id ?? null;
        $leads = [
            ['Rajesh Agarwal',  'rajesh@startup.io',   '+91-98100-11111', 'StartupIO',      'LinkedIn', 'new',          'HRMS software inquiry'],
            ['Meena Kapoor',    'meena@techfirm.in',   '+91-98100-22222', 'TechFirm',       'Referral', 'contacted',    'Interested in payroll module'],
            ['Sanjeev Pillai',  'sanjeev@corp.co',     '+91-98100-33333', 'Corp Solutions', 'Website',  'qualified',    'Full HRMS implementation'],
            ['Nisha Bhatt',     'nisha@sme.biz',       '+91-98100-44444', 'SME Biz',        'Cold Call','proposal_sent','Team of 50 employees'],
            ['Karan Mehta',     'karan@bigtrade.com',  '+91-98100-55555', 'BigTrade Co',    'LinkedIn', 'won',          'Signed 1yr contract'],
            ['Leela Shankar',   'leela@oldco.org',     '+91-98100-66666', 'OldCo',          'Email',    'lost',         'Went with competitor'],
        ];
        foreach ($leads as [$name, $email, $phone, $company, $source, $status, $desc]) {
            DB::table('shared.leads')->insertOrIgnore([
                'tenant_id'    => $this->tenant->id,
                'name'         => $name,
                'email'        => $email,
                'phone'        => $phone,
                'company_name' => $company,
                'source'       => $source,
                'status'       => $status,
                'assigned_to'  => $salesEmp,
                'description'  => $desc,
                'created_at'   => now()->subDays(rand(5, 30)),
                'updated_at'   => now(),
            ]);
        }
    }

    // ── PROJECTS ──────────────────────────────────────────────────────────
    private function seedProjects(): void
    {
        $this->command->info('🚀 Projects...');
        $clientId = array_values($this->clientMap)[0] ?? null;
        $projects = [
            ['hrms-core',      'Infosys Ltd',         'HRMS Core Platform v2',          'in_progress', 500000,  '-3 months', '+6 months'],
            ['mobile-app',     'Tata Consultancy',     'Employee Mobile App (iOS+Android)','in_progress',250000, '-1 month',  '+4 months'],
            ['payroll-engine', 'Wipro Ltd',            'Advanced Payroll Engine',         'completed',   180000, '-6 months', '-1 month'],
            ['internal-wiki',  null,                   'Internal Knowledge Base',         'in_progress', 50000,  '-2 months', '+2 months'],
            ['crm-integration','Zomato',               'CRM & Lead Management Module',    'planning',    120000, 'today',     '+5 months'],
        ];
        foreach ($projects as [$slug, $companyName, $name, $status, $budget, $startOff, $endOff]) {
            $cId = $companyName ? ($this->clientMap[$companyName] ?? null) : null;
            $id  = DB::table('shared.projects')->insertGetId([
                'tenant_id'   => $this->tenant->id,
                'client_id'   => $cId,
                'name'        => $name,
                'description' => "Demo project: {$name}",
                'start_date'  => now()->modify($startOff)->toDateString(),
                'deadline'    => now()->modify($endOff)->toDateString(),
                'status'      => $status,
                'budget'      => $budget,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            $this->projectMap[$slug] = $id;
        }
    }

    // ── TASKS & TIMESHEETS ────────────────────────────────────────────────
    private function seedTasksAndTimesheets(): void
    {
        $this->command->info('✅ Tasks & Timesheets...');
        $projectId = $this->projectMap['hrms-core'] ?? array_values($this->projectMap)[0] ?? null;
        if (!$projectId) return;

        $devEmpId  = $this->empMap['DEMO-003']->id ?? null;
        $devEmpId2 = $this->empMap['DEMO-001']->id ?? null;

        $tasks = [
            ['Build REST API for Employee Module', 'high',   'done',        $devEmpId2, '-10 days'],
            ['Design attendance dashboard UI',     'medium', 'in_progress', $devEmpId,  '+5 days'],
            ['Integrate payroll calculation engine','high',  'in_progress', $devEmpId2, '+10 days'],
            ['Write unit tests for Leave module',  'low',    'todo',        $devEmpId,  '+15 days'],
            ['Deploy to staging environment',      'high',   'todo',        $devEmpId2, '+20 days'],
        ];

        foreach ($tasks as [$title, $priority, $status, $assignedTo, $dueOff]) {
            $taskId = DB::table('shared.tasks')->insertGetId([
                'tenant_id'    => $this->tenant->id,
                'project_id'   => $projectId,
                'assigned_to'  => $assignedTo,
                'title'        => $title,
                'description'  => "Demo task: $title",
                'priority'     => $priority,
                'status'       => $status,
                'due_date'     => now()->modify($dueOff)->toDateString(),
                'completed_at' => $status === 'done' ? now()->subDays(2) : null,
                'created_at'   => now()->subDays(rand(5, 20)),
                'updated_at'   => now(),
            ]);

            // Timesheets for done/in_progress tasks
            if (in_array($status, ['done', 'in_progress']) && $assignedTo) {
                for ($d = 5; $d >= 1; $d--) {
                    $date = now()->subDays($d)->toDateString();
                    if (now()->subDays($d)->isWeekend()) continue;
                    DB::table('shared.timesheets')->insertOrIgnore([
                        'tenant_id'   => $this->tenant->id,
                        'employee_id' => $assignedTo,
                        'project_id'  => $projectId,
                        'task_id'     => $taskId,
                        'date'        => $date,
                        'hours'       => round(rand(2, 6) + rand(0,1) * 0.5, 1),
                        'description' => "Working on: $title",
                        'status'      => 'approved',
                        'approved_by' => $this->userMap['DEMO-001']->id ?? null,
                        'created_at'  => now()->subDays($d),
                        'updated_at'  => now(),
                    ]);
                }
            }
        }
    }

    // ── GOALS & KPIs ──────────────────────────────────────────────────────
    private function seedGoalsAndKPIs(): void
    {
        $this->command->info('🎯 Goals & KPIs...');

        // KPIs
        $kpis = [
            ['Revenue per Quarter', 'SALES', 5000000, 'INR (₹)'],
            ['New Leads per Month',  'SALES', 50,      'Count'],
            ['Tickets Resolved',     'OPS',   200,     'Count'],
            ['Bug Fix Rate',         'ENG',   95,      '%'],
            ['Employee Satisfaction','HR',    90,      '%'],
        ];
        foreach ($kpis as [$name, $dept, $target, $unit]) {
            DB::table('shared.kpis')->insertOrIgnore([
                'tenant_id'     => $this->tenant->id,
                'name'          => $name,
                'description'   => "KPI: {$name}",
                'department_id' => $this->deptMap[$dept]->id,
                'target_value'  => $target,
                'unit'          => $unit,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        // Goals
        $goalData = [
            ['DEMO-001', 'Complete HRMS v2 API',              '-1 month', '+3 months', 65,  'in_progress'],
            ['DEMO-003', 'Achieve 95% code coverage',          '-2 months','+1 month',  40,  'in_progress'],
            ['DEMO-007', 'Close ₹50L revenue this quarter',    '-1 month', '+2 months', 75,  'in_progress'],
            ['DEMO-009', 'Grow organic traffic by 40%',        '-2 months','+2 months', 30,  'in_progress'],
            ['DEMO-002', 'Reduce attrition to below 5%',       '-3 months','+3 months', 80,  'in_progress'],
            ['DEMO-010', 'Cut operational costs by 10%',       '-1 month', '+4 months', 55,  'in_progress'],
        ];
        foreach ($goalData as [$empId, $title, $startOff, $endOff, $progress, $status]) {
            $emp = $this->empMap[$empId] ?? null;
            if (!$emp) continue;
            DB::table('shared.goals')->insertOrIgnore([
                'tenant_id'           => $this->tenant->id,
                'employee_id'         => $emp->id,
                'title'               => $title,
                'description'         => "Goal: {$title}",
                'start_date'          => now()->modify($startOff)->toDateString(),
                'end_date'            => now()->modify($endOff)->toDateString(),
                'progress_percentage' => $progress,
                'status'              => $status,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }
    }

    // ── APPRAISALS ────────────────────────────────────────────────────────
    private function seedAppraisals(): void
    {
        $this->command->info('⭐ Appraisals...');
        $evaluatorId = $this->empMap['DEMO-002']->id ?? null; // HR Manager
        $appraisals  = [
            ['DEMO-001', 4.5, 'Excellent performance. Led the team effectively.', 'completed'],
            ['DEMO-003', 3.8, 'Good coder but needs to improve documentation.',   'completed'],
            ['DEMO-004', 4.0, 'Consistently hits sales targets.',                 'completed'],
            ['DEMO-007', 4.7, 'Outstanding leadership and client management.',    'completed'],
            ['DEMO-008', 3.2, 'Still learning, shows good potential.',            'in_review'],
            ['DEMO-011', 3.6, 'Good technical skills, needs more ownership.',     'in_review'],
        ];
        foreach ($appraisals as [$empId, $score, $comments, $status]) {
            $emp = $this->empMap[$empId] ?? null;
            if (!$emp) continue;
            DB::table('shared.appraisals')->insertOrIgnore([
                'tenant_id'     => $this->tenant->id,
                'employee_id'   => $emp->id,
                'evaluator_id'  => $evaluatorId,
                'review_period' => now()->year . ' Q1',
                'score'         => $score,
                'comments'      => $comments,
                'status'        => $status,
                'created_at'    => now()->subMonths(2),
                'updated_at'    => now(),
            ]);
        }
    }
}
