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

            // 2. Assign Baseline Tenant Staff Role
            $user->assignRole('tstaff');

            // 3. Create Employee linked to User
            $data['user_id'] = $user->id;
            $data['tenant_id'] = saas_tenant('id');

            return $this->repository->create($data);
        });
    }

    /**
     * Update an employee and sync their user account email if changed.
     */
    public function update(int|string $id, array $data)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($id, $data) {
            $employee = $this->repository->findOrFail($id);
            
            // Sync user email if it changed
            if (isset($data['email']) && $data['email'] !== $employee->email && $employee->user_id) {
                \App\Models\User::where('id', $employee->user_id)->update([
                    'email' => $data['email'],
                    'name' => ($data['first_name'] ?? $employee->first_name) . ' ' . ($data['last_name'] ?? $employee->last_name),
                ]);
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
