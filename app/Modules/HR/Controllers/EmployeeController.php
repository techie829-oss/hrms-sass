<?php

namespace App\Modules\HR\Controllers;

use App\Core\BaseController;
use App\Models\User;
use App\Modules\HR\Models\Department;
use App\Modules\HR\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class EmployeeController extends BaseController
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {}

    /**
     * Display employees listing.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['department_id', 'status', 'employment_type']);
        $search = $request->input('search');
        
        $employees = \App\Modules\HR\Models\Employee::with(['department', 'designation', 'user.roles', 'todayAttendance'])
            ->when($search, function($q) use ($search) {
                $q->where(function($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%")
                          ->orWhere('employee_id', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($filters['department_id'] ?? null, fn($q, $id) => $q->where('department_id', $id))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($filters['employment_type'] ?? null, fn($q, $type) => $q->where('employment_type', $type))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $departments = Department::all();

        return view('hr::employees.index', compact('employees', 'departments'));
    }

    /**
     * Show create employee form.
     */
    public function create(Request $request)
    {
        $departments = Department::all();
        $designations = \App\Modules\HR\Models\Designation::all();
        $roles = Role::where('tenant_id', saas_tenant('id'))->get();
        $employees = \App\Modules\HR\Models\Employee::active()->get();
        $permissions = \Spatie\Permission\Models\Permission::all();
        return view('hr::employees.create', compact('departments', 'designations', 'roles', 'employees', 'permissions'))->with($request->all());
    }

    /**
     * Store a new employee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'max:20', Rule::unique('employees')->where('tenant_id', saas_tenant('id'))],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('employees')->where('tenant_id', saas_tenant('id'))],
            'personal_email' => ['nullable', 'email'],
            'country_code' => ['nullable', 'string', 'max:10'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'date_of_birth' => ['nullable', 'date'],
            'pan_number' => ['nullable', 'string', 'max:20'],
            'aadhar_number' => ['nullable', 'string', 'max:20'],
            'passport_number' => ['nullable', 'string', 'max:20'],
            'current_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:255'],
            'reporting_to' => ['nullable', 'exists:employees,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'date_of_joining' => ['required', 'date'],
            'employment_type' => ['required', Rule::in(['full_time', 'part_time', 'contract', 'intern'])],
            'status' => ['required', Rule::in(['active', 'inactive', 'on_leave', 'terminated', 'resigned'])],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'checkin_required' => ['nullable', 'string', 'in:0,1,'],
            'create_login' => ['nullable', 'boolean'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'login_password' => ['nullable', 'string', 'min:8'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $employee = $this->employeeService->create($validated);

        // Create system login account if requested
        if ($request->boolean('create_login') && $employee) {
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                $user = User::create([
                    'tenant_id' => saas_tenant('id'),
                    'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($request->input('login_password', 'password')),
                    'email_verified_at' => now(),
                ]);
            }
            // Link employee to user
            $employee->update(['user_id' => $user->id]);

            // Assign role if selected
            if ($request->filled('role_id')) {
                $role = Role::find($request->role_id);
                if ($role) {
                    if (function_exists('setPermissionsTeamId')) {
                        setPermissionsTeamId(saas_tenant('id'));
                    }
                    $user->syncRoles([$role]);
                }
            }

            // Sync user-level permissions
            if ($request->has('permissions')) {
                if (function_exists('setPermissionsTeamId')) {
                    setPermissionsTeamId(saas_tenant('id'));
                }
                $user->syncPermissions($request->input('permissions', []));
            }
        }

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Show employee details.
     */
    public function show($id)
    {
        $employee = $this->employeeService->findOrFail($id);
        
        // Eager load relationships if needed, or fetch related data
        $employee->load(['department', 'appraisals', 'goals', 'documents', 'bankAccounts']);

        return view('hr::employees.show', compact('employee'));
    }

    /**
     * Show edit employee form.
     */
    public function edit($id)
    {
        $employee = $this->employeeService->findOrFail($id);
        $departments = Department::all();
        $designations = \App\Modules\HR\Models\Designation::all();
        $roles = Role::where('tenant_id', saas_tenant('id'))->get();
        $employees = \App\Modules\HR\Models\Employee::active()->where('id', '!=', $id)->get();
        $permissions = \Spatie\Permission\Models\Permission::all();

        return view('hr::employees.edit', compact('employee', 'departments', 'designations', 'roles', 'employees', 'permissions'));
    }

    /**
     * Update an employee.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'max:20', Rule::unique('employees')->where('tenant_id', saas_tenant('id'))->ignore($id)],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('employees')->where('tenant_id', saas_tenant('id'))->ignore($id)],
            'personal_email' => ['nullable', 'email'],
            'country_code' => ['nullable', 'string', 'max:10'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'date_of_birth' => ['nullable', 'date'],
            'pan_number' => ['nullable', 'string', 'max:20'],
            'aadhar_number' => ['nullable', 'string', 'max:20'],
            'passport_number' => ['nullable', 'string', 'max:20'],
            'current_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:255'],
            'reporting_to' => ['nullable', 'exists:employees,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'date_of_joining' => ['required', 'date'],
            'employment_type' => ['required', Rule::in(['full_time', 'part_time', 'contract', 'intern'])],
            'status' => ['required', Rule::in(['active', 'inactive', 'on_leave', 'terminated', 'resigned'])],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'checkin_required' => ['nullable', 'string', 'in:0,1,'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'create_login' => ['nullable', 'boolean'],
            'login_password' => ['nullable', 'string', 'min:8'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $employee = $this->employeeService->findOrFail($id);

        $this->employeeService->update($id, $validated);

        // Handle Role & Permissions update for existing login
        if ($employee->user_id) {
            $user = User::find($employee->user_id);
            if ($user) {
                if ($request->filled('role_id')) {
                    $role = Role::find($request->role_id);
                    if ($role) {
                        if (function_exists('setPermissionsTeamId')) {
                            setPermissionsTeamId(saas_tenant('id'));
                        }
                        $user->syncRoles([$role]);
                    }
                }
                
                if ($request->has('permissions')) {
                    if (function_exists('setPermissionsTeamId')) {
                        setPermissionsTeamId(saas_tenant('id'));
                    }
                    $user->syncPermissions($request->input('permissions', []));
                }
            }
        }
        // Handle new login creation & Permissions sync
        elseif ($request->boolean('create_login')) {
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                $user = User::create([
                    'tenant_id' => saas_tenant('id'),
                    'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($request->input('login_password', 'password')),
                    'email_verified_at' => now(),
                ]);
            }
            // Link employee to user
            $employee->update(['user_id' => $user->id]);

            // Assign role if selected
            if ($request->filled('role_id')) {
                $role = Role::find($request->role_id);
                if ($role) {
                    if (function_exists('setPermissionsTeamId')) {
                        setPermissionsTeamId(saas_tenant('id'));
                    }
                    $user->syncRoles([$role]);
                }
            }

            // Sync user-level permissions
            if ($request->has('permissions')) {
                if (function_exists('setPermissionsTeamId')) {
                    setPermissionsTeamId(saas_tenant('id'));
                }
                $user->syncPermissions($request->input('permissions', []));
            }
        }

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Delete an employee.
     */
    public function destroy($id)
    {
        $this->employeeService->delete($id);

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    /**
     * Upload an employee document securely.
     */
    public function uploadDocument(Request $request, $employeeId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'document_type' => 'required|string|in:offer_letter,experience,certificate,id_proof,other',
            'document_file' => 'required|file|mimes:pdf,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        $employee = $this->employeeService->findOrFail($employeeId);

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $tenantId = saas_tenant('id');
            
            // Store file securely inside local disk
            $path = $file->store("tenants/{$tenantId}/documents", 'local');

            \App\Modules\HR\Models\EmployeeDocument::create([
                'tenant_id' => $tenantId,
                'employee_id' => $employee->id,
                'title' => $request->title,
                'document_type' => $request->document_type,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);

            return back()->with('success', 'Document uploaded successfully.');
        }

        return back()->with('error', 'No file was uploaded.');
    }

    /**
     * Securely download an employee document.
     */
    public function downloadDocument($employeeId, $documentId)
    {
        $document = \App\Modules\HR\Models\EmployeeDocument::where('employee_id', $employeeId)->findOrFail($documentId);

        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File not found in storage.');
        }

        return \Illuminate\Support\Facades\Storage::disk('local')->download($document->file_path, $document->file_name);
    }

    /**
     * Permanently delete an employee document.
     */
    public function destroyDocument($employeeId, $documentId)
    {
        $document = \App\Modules\HR\Models\EmployeeDocument::where('employee_id', $employeeId)->findOrFail($documentId);

        // Delete from storage disk
        \Illuminate\Support\Facades\Storage::disk('local')->delete($document->file_path);

        // Delete from DB
        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }

    /**
     * Change employee system login password.
     */
    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $employee = $this->employeeService->findOrFail($id);

        if (!$employee->user_id) {
            return back()->with('error', 'This employee does not have a system login account.');
        }

        $user = User::findOrFail($employee->user_id);
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Store a bank account for the employee.
     */
    public function storeBankAccount(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:20',
            'account_number' => 'required|string|max:50',
            'branch_name' => 'nullable|string|max:255',
            'account_type' => 'required|string|in:savings,current',
            'is_primary' => 'nullable|boolean',
        ]);

        $employee = $this->employeeService->findOrFail($employeeId);

        // If is_primary is selected, mark all other bank accounts as not primary
        if ($request->boolean('is_primary')) {
            \App\Modules\HR\Models\EmployeeBankAccount::where('employee_id', $employee->id)
                ->update(['is_primary' => false]);
        }

        // If this is the employee's first bank account, make it primary automatically
        $hasAccounts = \App\Modules\HR\Models\EmployeeBankAccount::where('employee_id', $employee->id)->exists();
        $isPrimary = $request->boolean('is_primary') || !$hasAccounts;

        \App\Modules\HR\Models\EmployeeBankAccount::create([
            'tenant_id' => saas_tenant('id'),
            'employee_id' => $employee->id,
            'bank_name' => $validated['bank_name'],
            'ifsc_code' => strtoupper($validated['ifsc_code']),
            'account_number' => $validated['account_number'],
            'branch_name' => $validated['branch_name'] ?? null,
            'account_type' => $validated['account_type'],
            'is_primary' => $isPrimary,
        ]);

        return back()->with('success', 'Bank details added successfully.');
    }

    /**
     * Delete a bank account for the employee.
     */
    public function destroyBankAccount($employeeId, $bankAccountId)
    {
        $bankAccount = \App\Modules\HR\Models\EmployeeBankAccount::where('employee_id', $employeeId)
            ->findOrFail($bankAccountId);

        $wasPrimary = $bankAccount->is_primary;
        $bankAccount->delete();

        // If the deleted account was primary, set another account as primary if exists
        if ($wasPrimary) {
            $nextAccount = \App\Modules\HR\Models\EmployeeBankAccount::where('employee_id', $employeeId)->first();
            if ($nextAccount) {
                $nextAccount->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Bank details removed successfully.');
    }
}
