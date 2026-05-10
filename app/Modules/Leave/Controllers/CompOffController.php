<?php

namespace App\Modules\Leave\Controllers;

use App\Core\BaseController;
use App\Modules\Leave\Models\CompOffRequest;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompOffController extends BaseController
{
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

    public function updateStatus(Request $request, CompOffRequest $compOffRequest)
    {
        $this->authorize('update', $compOffRequest);
        
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($compOffRequest, $validated) {
            $compOffRequest->update([
                'status' => $validated['status'],
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // If approved, we could potentially add it to LeaveBalance.
            // But usually, it's a separate balance type.
        });

        return back()->with('success', 'Request ' . $validated['status']);
    }
}
