<?php

namespace App\Modules\Leave\Controllers;

use App\Core\BaseController;
use App\Modules\Leave\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends BaseController
{
    public function index()
    {
        $leaveTypes = LeaveType::all();
        return view('modules.leave.types.index', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10'],
            'max_days_per_year' => ['required', 'integer', 'min:0'],
            'is_paid' => ['boolean'],
            'is_carry_forward' => ['boolean'],
            'description' => ['nullable', 'string'],
        ]);

        LeaveType::create($validated);

        return redirect()->route('leave.types.index')
            ->with('success', 'Leave type created successfully.');
    }
}
