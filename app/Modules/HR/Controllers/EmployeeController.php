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
        $employees = $this->employeeService->all($filters);

        return view('hr::employees.index', compact('employees'));
    }

    /**
     * Show create employee form.
     */
    public function create(Request $request)
    {
        $departments = Department::all();
        $roles = Role::where('tenant_id', saas_tenant('id'))->get();
        return view('hr::employees.create', compact('departments', 'roles'))->with($request->all());
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
        $employee->load(['department', 'appraisals', 'goals', 'documents']);

        return view('hr::employees.show', compact('employee'));
    }

    /**
     * Show edit employee form.
     */
    public function edit($id)
    {
        $employee = $this->employeeService->findOrFail($id);
        $departments = Department::all();
        $roles = Role::where('tenant_id', saas_tenant('id'))->get();

        return view('hr::employees.edit', compact('employee', 'departments', 'roles'));
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
            'department_id' => ['nullable', 'exists:departments,id'],
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
}
