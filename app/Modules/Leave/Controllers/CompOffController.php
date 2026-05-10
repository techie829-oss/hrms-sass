<?php

namespace App\Modules\Leave\Controllers;

use App\Core\BaseController;
use App\Modules\Leave\Models\CompOffRequest;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompOffController extends BaseController
{
    protected $balanceService;

    public function __construct(\App\Modules\Leave\Services\LeaveBalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    public function index()
    {
        $this->authorize('viewAny', CompOffRequest::class);
        $user = Auth::user();
        
        $query = CompOffRequest::with('employee');
        
        if ($user->hasRole(\App\Core\Constants\RoleConstants::TSTAFF) && !$user->can('manage comp_off')) {
            $query->where('employee_id', $user->employee?->id);
        }

        $requests = $query->latest()->paginate(15);
        return view('leave::comp_off.index', compact('requests'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', CompOffRequest::class);
        $validated = $request->validate([
            'worked_on_date' => 'required|date',
            'duration' => 'required|numeric|in:0.5,1.0',
            'reason' => 'required|string|max:500',
        ]);

        CompOffRequest::create(array_merge($validated, [
            'tenant_id' => saas_tenant('id'),
            'employee_id' => Auth::user()->employee?->id,
            'status' => 'pending'
        ]));

        return back()->with('success', 'Comp-off request submitted.');
    }

    /**
     * Bulk grant comp-off to all employees present on a specific date.
     */
    public function bulkGrant(Request $request)
    {
        $this->authorize('manage_comp_off');
        
        $validated = $request->validate([
            'date' => 'required|date',
            'reason' => 'required|string|max:255',
        ]);

        $presentEmployees = \App\Modules\Attendance\Models\AttendanceLog::where('date', $validated['date'])
            ->where('tenant_id', saas_tenant('id'))
            ->pluck('employee_id')
            ->unique();

        $count = 0;
        foreach ($presentEmployees as $employeeId) {
            $employee = Employee::find($employeeId);
            if (!$employee) continue;

            // Create approved request
            CompOffRequest::create([
                'tenant_id' => saas_tenant('id'),
                'employee_id' => $employeeId,
                'worked_on_date' => $validated['date'],
                'duration' => 1.0,
                'reason' => $validated['reason'],
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Add to balance
            $this->balanceService->adjustBalance($employee, 'CO', 1.0);
            $count++;
        }

        return back()->with('success', "Comp-off granted to {$count} employees.");
    }

    public function updateStatus(Request $request, CompOffRequest $compOffRequest)
    {
        $this->authorize('manage_comp_off');
        
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($compOffRequest, $validated) {
            $compOffRequest->update([
                'status' => $validated['status'],
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            if ($validated['status'] === 'approved') {
                $this->balanceService->adjustBalance($compOffRequest->employee, 'CO', (float)$compOffRequest->duration);
            }
        });

    /**
     * One-click settlement of comp-offs.
     * Takes earned date and target usage date.
     */
    public function settleBulk(Request $request)
    {
        $this->authorize('manage_comp_off');

        $validated = $request->validate([
            'reference_date' => 'required|date', // Date worked
            'target_date' => 'required|date',    // Date used
        ]);

        $earnedCompOffs = CompOffRequest::where('worked_on_date', $validated['reference_date'])
            ->where('status', 'approved')
            ->where('is_used', false)
            ->where('tenant_id', saas_tenant('id'))
            ->get();

        $count = 0;
        $skipped = 0;

        foreach ($earnedCompOffs as $co) {
            $employee = $co->employee;

            // 1. Check if employee was actually PRESENT on the target_date
            $isPresent = \App\Modules\Attendance\Models\AttendanceLog::where('employee_id', $co->employee_id)
                ->where('date', $validated['target_date'])
                ->exists();

            if ($isPresent) {
                $skipped++;
                continue;
            }

            // 2. Auto-Apply and Approve Leave
            \Illuminate\Support\Facades\DB::transaction(function () use ($co, $employee, $validated) {
                $coType = \App\Modules\Leave\Models\LeaveType::where('code', 'CO')->first();
                
                $leaveRequest = \App\Modules\Leave\Models\LeaveRequest::create([
                    'tenant_id' => saas_tenant('id'),
                    'employee_id' => $co->employee_id,
                    'leave_type_id' => $coType->id,
                    'start_date' => $validated['target_date'],
                    'end_date' => $validated['target_date'],
                    'total_days' => 1.0,
                    'reason' => "Settlement for work on " . $validated['reference_date'],
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

                // 3. Mark Comp-Off as Used
                $co->update([
                    'is_used' => true,
                    'used_at' => $validated['target_date'],
                    'leave_request_id' => $leaveRequest->id
                ]);

                // 4. Update Balance
                $this->balanceService->adjustBalance($employee, 'CO', -1.0); // Deduct from balance as it's used
                // Note: adjustBalance increments, so we pass -1.0
            });

            $count++;
        }

        return back()->with('success', "Settled {$count} claims. Skipped {$skipped} employees (Present on target date).");
    }
}
