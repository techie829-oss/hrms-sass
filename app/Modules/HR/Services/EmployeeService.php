<?php

namespace App\Modules\HR\Services;

use App\Core\BaseService;
use App\Models\User;
use App\Modules\Attendance\Models\AttendanceEmployeeEnforcement;
use App\Modules\HR\DTOs\EmployeeData;
use App\Modules\HR\Events\EmployeeCreated;
use App\Modules\HR\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Modules\HR\Models\EmployeeDocument;
use App\Modules\HR\Models\EmployeeBankAccount;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
/**
 * @property EmployeeRepositoryInterface $repository
 */
class EmployeeService extends BaseService
{
    public function __construct(EmployeeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create a new employee and their associated user account.
     */
    public function createEmployee(EmployeeData $dto, array $loginData = [])
    {
        return DB::transaction(function () use ($dto, $loginData) {
            // 1. Create User
            $user = User::create([
                'tenant_id' => saas_tenant('id'),
                'name' => $dto->first_name.' '.$dto->last_name,
                'email' => $dto->email,
                'password' => Hash::make($loginData['login_password'] ?? 'password'),
                'email_verified_at' => now(),
            ]);

            // 2. Assign Role
            if (! empty($loginData['role_id'])) {
                $role = Role::find($loginData['role_id']);
                if ($role) {
                    if (function_exists('setPermissionsTeamId')) {
                        setPermissionsTeamId(saas_tenant('id'));
                    }
                    $user->assignRole($role);
                }
            } else {
                $role = Role::where('name', 'tstaff')->where('tenant_id', saas_tenant('id'))->first();
                if ($role) {
                    if (function_exists('setPermissionsTeamId')) {
                        setPermissionsTeamId(saas_tenant('id'));
                    }
                    $user->assignRole($role);
                }
            }

            // Sync user-level permissions
            if (! empty($loginData['permissions'])) {
                if (function_exists('setPermissionsTeamId')) {
                    setPermissionsTeamId(saas_tenant('id'));
                }
                $user->syncPermissions($loginData['permissions']);
            }

            // 3. Create Employee linked to User
            $employeeData = $dto->toArray();
            $employeeData['user_id'] = $user->id;
            $employeeData['tenant_id'] = saas_tenant('id');

            $checkinRequired = $dto->checkin_required;

            $employee = $this->repository->create($employeeData);

            // Create enforcement record if specified
            if ($checkinRequired !== null && $checkinRequired !== '') {
                $enforceKiosk = ($checkinRequired == '1') ? 1 : 2; // 1 = Force, 2 = Exempt
                AttendanceEmployeeEnforcement::updateOrCreate(
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
            EmployeeCreated::dispatch($employee);

            return $employee;
        });
    }

    /**
     * Update an employee and sync their user account email if changed.
     */
    public function updateEmployee(int|string $id, EmployeeData $dto, array $loginData = [])
    {
        return DB::transaction(function () use ($id, $dto, $loginData) {
            $employee = $this->repository->findOrFail($id);

            // Sync user details
            if ($employee->user_id) {
                $userUpdate = [];
                if ($dto->email !== $employee->email) {
                    $userUpdate['email'] = $dto->email;
                }
                if ($dto->first_name !== $employee->first_name || $dto->last_name !== $employee->last_name) {
                    $userUpdate['name'] = $dto->first_name.' '.$dto->last_name;
                }

                if (! empty($userUpdate)) {
                    User::where('id', $employee->user_id)->update($userUpdate);
                }

                $user = User::find($employee->user_id);
                if ($user) {
                    if (function_exists('setPermissionsTeamId')) {
                        setPermissionsTeamId(saas_tenant('id'));
                    }

                    if (! empty($loginData['role_id'])) {
                        $role = Role::find($loginData['role_id']);
                        if ($role) {
                            $user->syncRoles([$role]);
                        }
                    }

                    if (isset($loginData['permissions'])) {
                        $user->syncPermissions($loginData['permissions']);
                    }
                }
            } elseif (! empty($loginData['create_login'])) {
                // Create user if requested later
                $user = User::create([
                    'tenant_id' => saas_tenant('id'),
                    'name' => $dto->first_name.' '.$dto->last_name,
                    'email' => $dto->email,
                    'password' => Hash::make($loginData['login_password'] ?? 'password'),
                    'email_verified_at' => now(),
                ]);
                $employee->update(['user_id' => $user->id]);

                if (function_exists('setPermissionsTeamId')) {
                    setPermissionsTeamId(saas_tenant('id'));
                }

                if (! empty($loginData['role_id'])) {
                    $role = Role::find($loginData['role_id']);
                    if ($role) {
                        $user->syncRoles([$role]);
                    }
                } else {
                    $role = Role::where('name', 'tstaff')->where('tenant_id', saas_tenant('id'))->first();
                    if ($role) {
                        $user->assignRole($role);
                    }
                }

                if (! empty($loginData['permissions'])) {
                    $user->syncPermissions($loginData['permissions']);
                }
            }

            // Sync checkin_required (AttendanceEmployeeEnforcement)
            $checkinRequired = $dto->checkin_required;
            if ($checkinRequired !== null && $checkinRequired !== '') {
                $enforceKiosk = ($checkinRequired == '1') ? 1 : 2; // 1 = Force, 2 = Exempt
                AttendanceEmployeeEnforcement::updateOrCreate(
                    [
                        'tenant_id' => saas_tenant('id'),
                        'employee_id' => $employee->id,
                    ],
                    [
                        'enforce_kiosk' => $enforceKiosk,
                    ]
                );
            } else {
                AttendanceEmployeeEnforcement::where('employee_id', $employee->id)->delete();
            }

            return $this->repository->update($id, $dto->toArray());
        });
    }

    /**
     * Delete an employee and their user account.
     */
    public function delete(int|string $id): bool
    {
        return DB::transaction(function () use ($id) {
            $employee = $this->repository->findOrFail($id);
            $userId = $employee->user_id;

            $deleted = $this->repository->delete($id);

            if ($deleted && $userId) {
                User::where('id', $userId)->delete();
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

    /**
     * Get all active employees.
     */
    public function getActiveEmployees()
    {
        return $this->repository->getActiveEmployees();
    }

    /**
     * Upload an employee document securely.
     */
    public function uploadDocument(int $employeeId, array $data, $file)
    {
        $employee = $this->repository->findOrFail($employeeId);
        $tenantId = saas_tenant('id');

        $path = $file->store("tenants/{$tenantId}/documents", 'local');

        return EmployeeDocument::create([
            'tenant_id' => $tenantId,
            'employee_id' => $employee->id,
            'title' => $data['title'],
            'document_type' => $data['document_type'],
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);
    }

    /**
     * Permanently delete an employee document.
     */
    public function deleteDocument(int $employeeId, int $documentId)
    {
        $document = EmployeeDocument::where('employee_id', $employeeId)->findOrFail($documentId);

        Storage::disk('local')->delete($document->file_path);
        
        return $document->delete();
    }

    /**
     * Change employee system login password.
     */
    public function changePassword(int $employeeId, string $newPassword)
    {
        $employee = $this->repository->findOrFail($employeeId);

        if (! $employee->user_id) {
            throw new \Exception('This employee does not have a system login account.');
        }

        return User::where('id', $employee->user_id)->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Store a bank account for the employee.
     */
    public function addBankAccount(int $employeeId, array $data, bool $isPrimaryReq)
    {
        $employee = $this->repository->findOrFail($employeeId);

        if ($isPrimaryReq) {
            EmployeeBankAccount::where('employee_id', $employee->id)
                ->update(['is_primary' => false]);
        }

        $hasAccounts = EmployeeBankAccount::where('employee_id', $employee->id)->exists();
        $isPrimary = $isPrimaryReq || ! $hasAccounts;

        return EmployeeBankAccount::create([
            'tenant_id' => saas_tenant('id'),
            'employee_id' => $employee->id,
            'bank_name' => $data['bank_name'],
            'ifsc_code' => strtoupper($data['ifsc_code']),
            'account_number' => $data['account_number'],
            'branch_name' => $data['branch_name'] ?? null,
            'account_type' => $data['account_type'],
            'is_primary' => $isPrimary,
        ]);
    }

    /**
     * Delete a bank account for the employee.
     */
    public function deleteBankAccount(int $employeeId, int $bankAccountId)
    {
        $bankAccount = EmployeeBankAccount::where('employee_id', $employeeId)
            ->findOrFail($bankAccountId);

        $wasPrimary = $bankAccount->is_primary;
        $bankAccount->delete();

        if ($wasPrimary) {
            $nextAccount = EmployeeBankAccount::where('employee_id', $employeeId)->first();
            if ($nextAccount) {
                $nextAccount->update(['is_primary' => true]);
            }
        }

        return true;
    }
}
