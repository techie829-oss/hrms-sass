<?php

namespace App\Modules\Leave\Controllers;

use App\Core\BaseController;
use App\Modules\Leave\Models\LeaveRequest;
use App\Modules\Leave\Models\LeaveType;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends BaseController
{
    /**
     * Display a listing of leave requests.
     */
    public function index()
    {
        $this->authorize('viewAny', LeaveRequest::class);
        
        $user = Auth::user();
        $query = LeaveRequest::with(['employee', 'leaveType']);

        // Staff can only see their own requests unless they have broader view permissions
        if (!$user->can('approve leave')) {
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
        
        $isAdmin = $user->can('approve leave');
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
        if (!$request->user()->can('approve leave')) {
            $validated['employee_id'] = $request->user()->employee?->id;
        }

        // Logic to calculate total days
        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end = \Carbon\Carbon::parse($validated['end_date']);
        $totalDays = $start->diffInDays($end) + 1;
        
        if ($validated['is_half_day'] ?? false) {
            $totalDays = 0.5;
        }

        // Check Balance
        $balance = \App\Modules\Leave\Models\LeaveBalance::where([
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

        $balance->increment('total_pending', $totalDays);

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
                $balance = \App\Modules\Leave\Models\LeaveBalance::where([
                    'employee_id' => $leaveRequest->employee_id,
                    'leave_type_id' => $leaveRequest->leave_type_id,
                    'year' => $leaveRequest->start_date->year,
                ])->first();

                if ($balance) {
                    $balance->decrement('total_pending', $leaveRequest->total_days);
                    
                    if ($validated['status'] === 'approved') {
                        $balance->decrement('balance', $leaveRequest->total_days);
                        $balance->increment('total_used', $leaveRequest->total_days);

                        // Comp-Off Settlement Logic
                        $coType = \App\Modules\Leave\Models\LeaveType::where('code', 'CO')->first();
                        if ($coType && $leaveRequest->leave_type_id === $coType->id) {
                            $unsettled = \App\Modules\Leave\Models\CompOffRequest::where('employee_id', $leaveRequest->employee_id)
                                ->where('status', 'approved')
                                ->where('is_used', false)
                                ->orderBy('worked_on_date')
                                ->limit((int)$leaveRequest->total_days)
                                ->get();

                            foreach ($unsettled as $co) {
                                $co->update([
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

        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end = \Carbon\Carbon::parse($validated['end_date']);
        $totalDays = $start->diffInDays($end) + 1;

        $successCount = 0;
        $errors = [];

        foreach ($validated['employee_ids'] as $employeeId) {
            $employee = Employee::find($employeeId);
            
            // Check Balance
            $balance = \App\Modules\Leave\Models\LeaveBalance::where([
                'employee_id' => $employeeId,
                'leave_type_id' => $validated['leave_type_id'],
                'year' => $start->year,
            ])->first();

            if (!$balance || $balance->balance < $totalDays) {
                $errors[] = "Employee {$employee->full_name} has insufficient balance.";
                continue;
            }

            // Create Request (Auto-Approve if bulk by admin?) 
            // Usually bulk apply by admin is auto-approved.
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $totalDays, $employeeId, $balance, $start) {
                $leaveRequest = LeaveRequest::create([
                    'tenant_id' => saas_tenant('id'),
                    'employee_id' => $employeeId,
                    'leave_type_id' => $validated['leave_type_id'],
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                    'total_days' => $totalDays,
                    'reason' => $validated['reason'],
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

                // Update Balance
                $balance->decrement('balance', $totalDays);
                $balance->increment('total_used', $totalDays);

                // Comp-Off Settlement
                $coType = \App\Modules\Leave\Models\LeaveType::where('code', 'CO')->first();
                if ($coType && (int)$validated['leave_type_id'] === $coType->id) {
                    $unsettled = \App\Modules\Leave\Models\CompOffRequest::where('employee_id', $employeeId)
                        ->where('status', 'approved')
                        ->where('is_used', false)
                        ->orderBy('worked_on_date')
                        ->limit((int)$totalDays)
                        ->get();

                    foreach ($unsettled as $co) {
                        $co->update([
                            'is_used' => true,
                            'used_at' => $start,
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
