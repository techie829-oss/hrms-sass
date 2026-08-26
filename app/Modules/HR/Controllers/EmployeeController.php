<?php

namespace App\Modules\HR\Controllers;

use App\Core\BaseController;
use App\Models\User;
use App\Modules\HR\Models\Department;
use App\Modules\HR\Models\Designation;
use App\Modules\HR\Models\Employee;
use App\Modules\HR\Models\EmployeeBankAccount;
use App\Modules\HR\Models\EmployeeDocument;
use App\Modules\HR\Requests\StoreEmployeeRequest;
use App\Modules\HR\Requests\UpdateEmployeeRequest;
use App\Modules\HR\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
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
        $this->authorize('viewAny', Employee::class);

        $filters = $request->only(['department_id', 'status', 'employment_type']);
        $search = $request->input('search');

        $employees = Employee::with(['department', 'designation', 'user.roles', 'todayAttendance'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('employee_id', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($filters['department_id'] ?? null, fn ($q, $id) => $q->where('department_id', $id))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['employment_type'] ?? null, fn ($q, $type) => $q->where('employment_type', $type))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $departments = Department::all();

        $stats = [
            'total' => Employee::count(),
            'active' => Employee::where('status', 'active')->count(),
            'on_leave' => Employee::where('status', 'on_leave')->count(),
        ];

        return view('hr::employees.index', compact('employees', 'departments', 'stats'));
    }

    /**
     * Show create employee form.
     */
    public function create(Request $request)
    {
        $this->authorize('create', Employee::class);

        $departments = Department::all();
        $designations = Designation::all();
        $roles = Role::where('tenant_id', saas_tenant('id'))->get();
        $employees = Employee::active()->get();
        $permissions = Permission::all();

        return view('hr::employees.create', compact('departments', 'designations', 'roles', 'employees', 'permissions'))->with($request->all());
    }

    /**
     * Store a new employee.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $this->authorize('create', Employee::class);

        $dto = $request->toDTO();
        $loginData = $request->getLoginData();

        $employee = $this->employeeService->createEmployee($dto, $loginData);

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Show employee details.
     */
    public function show($id)
    {
        $employee = $this->employeeService->findOrFail($id);
        $this->authorize('view', $employee);

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
        $this->authorize('update', $employee);

        $departments = Department::all();
        $designations = Designation::all();
        $roles = Role::where('tenant_id', saas_tenant('id'))->get();
        $employees = Employee::active()->where('id', '!=', $id)->get();
        $permissions = Permission::all();

        return view('hr::employees.edit', compact('employee', 'departments', 'designations', 'roles', 'employees', 'permissions'));
    }

    /**
     * Update an employee.
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        $employee = $this->employeeService->findOrFail($id);
        $this->authorize('update', $employee);

        $dto = $request->toDTO();
        $loginData = $request->getLoginData();

        $this->employeeService->updateEmployee($id, $dto, $loginData);

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Delete an employee.
     */
    public function destroy($id)
    {
        $employee = $this->employeeService->findOrFail($id);
        $this->authorize('delete', $employee);

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
        $this->authorize('update', $employee);

        if ($request->hasFile('document_file')) {
            $this->employeeService->uploadDocument($employeeId, $request->only('title', 'document_type'), $request->file('document_file'));
            return back()->with('success', 'Document uploaded successfully.');
        }

        return back()->with('error', 'No file was uploaded.');
    }

    /**
     * Securely download an employee document.
     */
    public function downloadDocument($employeeId, $documentId)
    {
        $document = EmployeeDocument::where('employee_id', $employeeId)->findOrFail($documentId);
        $this->authorize('view', $document->employee);

        if (! Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File not found in storage.');
        }

        return Storage::disk('local')->download($document->file_path, $document->file_name);
    }

    /**
     * Permanently delete an employee document.
     */
    public function destroyDocument($employeeId, $documentId)
    {
        $document = EmployeeDocument::where('employee_id', $employeeId)->findOrFail($documentId);
        $this->authorize('update', $document->employee);

        $this->employeeService->deleteDocument($employeeId, $documentId);

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
        $this->authorize('update', $employee);

        try {
            $this->employeeService->changePassword($id, $request->password);
            return back()->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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
        $this->authorize('update', $employee);

        $this->employeeService->addBankAccount($employeeId, $validated, $request->boolean('is_primary'));

        return back()->with('success', 'Bank details added successfully.');
    }

    /**
     * Delete a bank account for the employee.
     */
    public function destroyBankAccount($employeeId, $bankAccountId)
    {
        $bankAccount = EmployeeBankAccount::where('employee_id', $employeeId)
            ->findOrFail($bankAccountId);
        
        $this->authorize('update', $bankAccount->employee);

        $this->employeeService->deleteBankAccount($employeeId, $bankAccountId);

        return back()->with('success', 'Bank details removed successfully.');
    }
}
