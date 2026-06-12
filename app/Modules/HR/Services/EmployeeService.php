<?php

namespace App\Modules\HR\Services;

use App\Core\BaseService;
use App\Modules\HR\Interfaces\EmployeeRepositoryInterface;

class EmployeeService extends BaseService
{
    public function __construct(EmployeeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create a new employee and their associated user account.
     */
    public function create(array $data)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            // 1. Create User
            $user = \App\Models\User::create([
                'tenant_id' => saas_tenant('id'),
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => \Illuminate\Support\Facades\Hash::make('password'), // Default password
                'email_verified_at' => now(),
            ]);

            // 2. Assign Baseline Tenant Staff Role (pass explicit model to avoid global fallback)
            $role = \Spatie\Permission\Models\Role::where('name', 'tstaff')->where('tenant_id', saas_tenant('id'))->first();
            if ($role) {
                $user->assignRole($role);
            } else {
                $user->assignRole('tstaff'); // Fallback if tenant-specific role is missing
            }

            // 3. Create Employee linked to User
            $data['user_id'] = $user->id;
            $data['tenant_id'] = saas_tenant('id');

            $checkinRequired = $data['checkin_required'] ?? null;
            unset($data['checkin_required'], $data['create_login'], $data['role_id'], $data['login_password']);

            $employee = $this->repository->create($data);

            // Create enforcement record if specified
            if ($checkinRequired !== null && $checkinRequired !== '') {
                $enforceKiosk = ($checkinRequired == '1') ? 1 : 2; // 1 = Force, 2 = Exempt
                \App\Modules\Attendance\Models\AttendanceEmployeeEnforcement::updateOrCreate(
                    [
                        'tenant_id' => saas_tenant('id'),
                        'employee_id' => $employee->id,
                    ],
                    [
                        'enforce_kiosk' => $enforceKiosk,
                    ]
                );
            }

            // 4. Dispatch Event
            \App\Modules\HR\Events\EmployeeCreated::dispatch($employee);

            return $employee;
        });
    }

    /**
     * Update an employee and sync their user account email if changed.
     */
    public function update(int|string $id, array $data)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($id, $data) {
            $employee = $this->repository->findOrFail($id);
            
            // Sync user details and checkin_required
            if ($employee->user_id) {
                $userUpdate = [];
                if (isset($data['email'])) {
                    $userUpdate['email'] = $data['email'];
                }
                if (isset($data['first_name']) || isset($data['last_name'])) {
                    $userUpdate['name'] = ($data['first_name'] ?? $employee->first_name) . ' ' . ($data['last_name'] ?? $employee->last_name);
                }

                if (!empty($userUpdate)) {
                    \App\Models\User::where('id', $employee->user_id)->update($userUpdate);
                }
            }

            // Sync checkin_required (AttendanceEmployeeEnforcement)
            if (array_key_exists('checkin_required', $data)) {
                $checkinRequired = $data['checkin_required'];
                if ($checkinRequired !== null && $checkinRequired !== '') {
                    $enforceKiosk = ($checkinRequired == '1') ? 1 : 2; // 1 = Force, 2 = Exempt
                    \App\Modules\Attendance\Models\AttendanceEmployeeEnforcement::updateOrCreate(
                        [
                            'tenant_id' => saas_tenant('id'),
                            'employee_id' => $employee->id,
                        ],
                        [
                            'enforce_kiosk' => $enforceKiosk,
                        ]
                    );
                } else {
                    \App\Modules\Attendance\Models\AttendanceEmployeeEnforcement::where('employee_id', $employee->id)->delete();
                }
                unset($data['checkin_required']);
            }

            return $this->repository->update($id, $data);
        });
    }

    /**
     * Delete an employee and their user account.
     */
    public function delete(int|string $id): bool
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($id) {
            $employee = $this->repository->findOrFail($id);
            $userId = $employee->user_id;
            
            $deleted = $this->repository->delete($id);
            
            if ($deleted && $userId) {
                \App\Models\User::where('id', $userId)->delete();
            }
            
            return $deleted;
        });
    }

    /**
     * Get all employees with pagination and filters.
     */
    public function all(array $filters = [])
    {
        return $this->repository->paginate(15, $filters);
    }

    /**
     * Get employees by department.
     */
    public function getByDepartment(int $departmentId)
    {
        return $this->repository->findByDepartment($departmentId);
    }

    /**
     * Get active employees count.
     */
    public function getActiveCount(): int
    {
        return $this->repository->countActive();
    }
}
