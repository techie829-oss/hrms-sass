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
        $requests = LeaveRequest::with(['employee', 'leaveType'])
            ->latest()
            ->paginate(15);

        return view('leave::index', compact('requests'));
    }

    /**
     * Show form to create a new leave request.
     */
    public function create()
    {
        $leaveTypes = LeaveType::where('is_active', true)->get();
        $employees = Employee::where('status', 'active')->get(); // For admin view

        return view('leave::create', compact('leaveTypes', 'employees'));
    }

    /**
     * Store a newly created leave request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_half_day' => ['boolean'],
            'half_day_type' => ['nullable', 'required_if:is_half_day,1', 'in:first_half,second_half'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        // Logic to calculate total days would normally go in a Service
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
            'total_days' => $totalDays,
            'status' => 'pending',
        ]));

        if ($balance) {
            $balance->increment('total_pending', $totalDays);
        }

        return redirect()->route('leave.requests.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Approve or reject a leave request.
     */
    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
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
                    }
                }
            }
        });

        return redirect()->route('leave.requests.index')
            ->with('success', 'Leave request status updated.');
    }

    /**
     * Display a single leave request.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['employee', 'leaveType']);

        return view('leave::show', compact('leaveRequest'));
    }
}
