<?php

namespace App\Modules\Leave\Controllers;

use App\Core\BaseController;
use App\Modules\Leave\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends BaseController
{
    public function index()
    {
        \Illuminate\Support\Facades\Gate::authorize('manage-settings');
        $leaveTypes = LeaveType::all();
        return view('leave::types.index', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('manage-settings');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10'],
            'max_days_per_year' => ['required', 'integer', 'min:0'],
            'is_paid' => ['boolean'],
            'is_carry_forward' => ['boolean'],
            'applicable_in_probation' => ['boolean'],
            'description' => ['nullable', 'string'],
        ]);

        LeaveType::create(array_merge($validated, ['tenant_id' => saas_tenant('id')]));

        return redirect()->route('leave.types.index')
            ->with('success', 'Leave type created successfully.');
    }
}
