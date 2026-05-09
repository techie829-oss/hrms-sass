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
        
        $employees = \App\Modules\HR\Models\Employee::with(['department', 'designation', 'user.roles', 'todayAttendance'])
            ->when($filters['department_id'] ?? null, fn($q, $id) => $q->where('department_id', $id))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($filters['employment_type'] ?? null, fn($q, $type) => $q->where('employment_type', $type))
            ->latest()
            ->paginate(15);

        return view('hr::employees.index', compact('employees'));
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
        return view('hr::employees.create', compact('departments', 'designations', 'roles', 'employees'))->with($request->all());
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
                    setPermissionsTeamId(saas_tenant('id'));
                    $user->syncRoles([$role]);
                }
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

        return view('hr::employees.edit', compact('employee', 'departments', 'designations', 'roles', 'employees'));
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
        ]);

        $this->employeeService->update($id, $validated);

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
}
