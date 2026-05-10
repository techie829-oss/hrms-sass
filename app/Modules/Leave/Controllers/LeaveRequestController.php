<?php

namespace App\Modules\Leave\Controllers;

use App\Core\BaseController;
use App\Modules\Leave\Models\LeaveRequest;
use App\Modules\Leave\Models\LeaveType;
use App\Modules\Leave\Models\LeaveBalance;
use App\Modules\Leave\Models\CompOffRequest;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends BaseController
{
    protected $calculationService;
    protected $balanceService;

    public function __construct(
        \App\Modules\Leave\Services\LeaveCalculationService $calculationService,
        \App\Modules\Leave\Services\LeaveBalanceService $balanceService
    ) {
        $this->calculationService = $calculationService;
        $this->balanceService = $balanceService;
    }

    /**
     * Display a listing of leave requests.
     */
    public function index()
    {
        $this->authorize('viewAny', LeaveRequest::class);
        
        $user = Auth::user();
        $query = LeaveRequest::with(['employee', 'leaveType']);

        // Staff can only see their own requests unless they have broader view permissions
        if (!$user->can('approve-leave')) {
            $employee = $user->employee;
            $query->where('employee_id', $employee->id ?? 0);
        }

        $requests = $query->latest()->paginate(15);
        $employees = Employee::active()->get();
        $leaveTypes = LeaveType::where('is_active', true)->get();

        return view('leave::index', compact('requests', 'employees', 'leaveTypes'));
    }

    /**
     * Show form to create a new leave request.
     */
    public function create()
    {
        $this->authorize('create', LeaveRequest::class);
        
        $leaveTypes = LeaveType::where('is_active', true)->get();
        $user = Auth::user();
        
        $isAdmin = $user->can('approve-leave');
        $employees = $isAdmin 
            ? Employee::active()->get() 
            : Employee::where('user_id', $user->id)->get();

        $employee = $user->employee;
        $balances = [];
        if ($employee) {
            $balances = \App\Modules\Leave\Models\LeaveBalance::with('leaveType')
                ->where('employee_id', $employee->id)
                ->where('year', now()->year)
                ->get();
        }

        return view('leave::create', compact('leaveTypes', 'employees', 'isAdmin', 'balances'));
    }

    /**
     * Store a newly created leave request.
     */
    public function store(Request $request)
    {
        $this->authorize('create', LeaveRequest::class);

        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_half_day' => ['boolean'],
            'half_day_type' => ['nullable', 'required_if:is_half_day,1', 'in:first_half,second_half'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        // Security check: Staff cannot apply for others
        if (!$request->user()->can('approve-leave')) {
            $validated['employee_id'] = $request->user()->employee?->id;
        }

        // Edge Case Check: Half-day must be same day
        if (($validated['is_half_day'] ?? false) && $validated['start_date'] !== $validated['end_date']) {
            return back()->with('error', 'Half-day leave can only be applied for a single day (Start and End date must be same).');
        }

        $employee = Employee::find($validated['employee_id']);
        $leaveType = \App\Modules\Leave\Models\LeaveType::find($validated['leave_type_id']);

        // Probation Check
        if ($employee && $employee->is_on_probation && !$leaveType->applicable_in_probation) {
            return back()->with('error', "Your account is currently in the probation period. {$leaveType->name} is not applicable during probation.");
        }

        // Logic to calculate total days excluding holidays/weekends
        $totalDays = $this->calculationService->calculateNetDays($validated['start_date'], $validated['end_date'], $validated['employee_id']);
        
        if ($validated['is_half_day'] ?? false) {
            $totalDays = 0.5;
        }

        if ($totalDays <= 0) {
            return back()->with('error', 'Selected dates are already holidays or weekends.');
        }

        // Check Balance
        $balance = LeaveBalance::where([
            'employee_id' => $validated['employee_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'year' => now()->year,
        ])->first();

        if (!$balance) {
            return back()->with('error', 'No leave balance has been allocated for this employee for ' . now()->year . '.');
        }

        if ($balance->balance < $totalDays) {
            return back()->with('error', 'Insufficient leave balance. Remaining: ' . $balance->balance . ' days.');
        }

        $leaveRequest = LeaveRequest::create(array_merge($validated, [
            'tenant_id' => saas_tenant('id'),
            'total_days' => $totalDays,
            'status' => 'pending',
        ]));

        LeaveBalance::where('id', $balance->id)->increment('total_pending', $totalDays);

        return redirect()->route('leave.requests.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Approve or reject a leave request.
     */
    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('approve', $leaveRequest);

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'rejection_reason' => ['nullable', 'required_if:status,rejected', 'string'],
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($leaveRequest, $validated) {
            $oldStatus = $leaveRequest->status;
            
            $leaveRequest->update([
                'status' => $validated['status'],
                'rejection_reason' => $validated['rejection_reason'] ?? null,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            if ($oldStatus === 'pending') {
                $balance = LeaveBalance::where([
                    'employee_id' => $leaveRequest->employee_id,
                    'leave_type_id' => $leaveRequest->leave_type_id,
                    'year' => $leaveRequest->start_date->year,
                ])->first();

                if ($balance) {
                    LeaveBalance::where('id', $balance->id)->decrement('total_pending', $leaveRequest->total_days);
                    
                    if ($validated['status'] === 'approved') {
                        LeaveBalance::where('id', $balance->id)->decrement('balance', $leaveRequest->total_days);
                        LeaveBalance::where('id', $balance->id)->increment('total_used', $leaveRequest->total_days);

                        // Comp-Off Settlement Logic
                        $coType = LeaveType::where('code', 'CO')->first();
                        if ($coType && $leaveRequest->leave_type_id === $coType->id) {
                            $unsettled = CompOffRequest::where('employee_id', $leaveRequest->employee_id)
                                ->where('status', 'approved')
                                ->where('is_used', false)
                                ->orderBy('worked_on_date')
                                ->limit((int)$leaveRequest->total_days)
                                ->get();

                            foreach ($unsettled as $co) {
                                CompOffRequest::where('id', $co->id)->update([
                                    'is_used' => true,
                                    'used_at' => $leaveRequest->start_date,
                                    'leave_request_id' => $leaveRequest->id
                                ]);
                            }
                        }
                    }
                }
            }
        });

        return redirect()->route('leave.requests.index')
            ->with('success', 'Leave request status updated.');
    }

    /**
     * Store multiple leave requests at once (Bulk Apply).
     */
    public function bulkStore(Request $request)
    {
        $this->authorize('create', LeaveRequest::class);

        $validated = $request->validate([
            'employee_ids' => ['required', 'array'],
            'employee_ids.*' => ['exists:employees,id'],
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $successCount = 0;
        $errors = [];
        $start = \Carbon\Carbon::parse($validated['start_date']);
        
        // Optimization: Fetch all employees at once to avoid N+1
        $employees = Employee::whereIn('id', $validated['employee_ids'])->get()->keyBy('id');
        
        // Optimization: Fetch CO type once
        $coType = LeaveType::where('code', 'CO')->first();

        foreach ($validated['employee_ids'] as $employeeId) {
            $employee = $employees->get($employeeId);
            if (!$employee) {
                $errors[] = "Employee ID {$employeeId} not found.";
                continue;
            }

            $totalDays = $this->calculationService->calculateNetDays($validated['start_date'], $validated['end_date'], $employeeId);
            
            if ($totalDays <= 0) {
                $errors[] = "Employee {$employee->first_name}: Selected dates are holidays/weekends.";
                continue;
            }

            // Probation Check
            $leaveType = LeaveType::find($validated['leave_type_id']);
            if ($employee->is_on_probation && $leaveType && !$leaveType->applicable_in_probation) {
                $errors[] = "Employee {$employee->first_name} is in probation. {$leaveType->name} is not applicable.";
                continue;
            }
            
            // Check Balance
            $balance = LeaveBalance::where([
                'employee_id' => $employeeId,
                'leave_type_id' => $validated['leave_type_id'],
                'year' => $start->year,
            ])->first();

            if (!$balance || $balance->balance < $totalDays) {
                $errors[] = "Employee {$employee->first_name} has insufficient balance.";
                continue;
            }

            // Create Request (Auto-Approve for bulk by admin) 
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $totalDays, $employeeId, $balance, $start, $coType) {
                $leaveRequest = LeaveRequest::create([
                    'tenant_id' => saas_tenant('id'),
                    'employee_id' => $employeeId,
                    'leave_type_id' => $validated['leave_type_id'],
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                    'total_days' => $totalDays,
                    'is_half_day' => false,
                    'reason' => $validated['reason'],
                    'status' => 'approved', 
                    'applied_on' => now(),
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);

                // Update Balance
                LeaveBalance::where('id', $balance->id)->decrement('balance', $totalDays);
                LeaveBalance::where('id', $balance->id)->increment('total_used', $totalDays);

                // Comp-Off Settlement
                if ($coType && (int)$validated['leave_type_id'] === $coType->id) {
                    $unsettled = CompOffRequest::where('employee_id', $employeeId)
                        ->where('status', 'approved')
                        ->where('is_used', false)
                        ->orderBy('worked_on_date')
                        ->limit(ceil($totalDays))
                        ->get();

                    foreach ($unsettled as $co) {
                        CompOffRequest::where('id', $co->id)->update([
                            'is_used' => true,
                            'used_at' => $start->format('Y-m-d'),
                            'leave_request_id' => $leaveRequest->id
                        ]);
                    }
                }
            });

            $successCount++;
        }

        $message = "Bulk leave applied for {$successCount} employees.";
        if (count($errors) > 0) {
            $message .= " Errors: " . implode(', ', $errors);
        }

        return redirect()->route('leave.requests.index')->with('success', $message);
    }

    /**
     * Display a single leave request.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);
        $leaveRequest->load(['employee', 'leaveType']);

        return view('leave::show', compact('leaveRequest'));
    }
}
