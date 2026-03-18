<?php

namespace App\Modules\Performance\Controllers;

use App\Core\BaseController;
use App\Modules\Performance\Models\Appraisal;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppraisalController extends BaseController
{
    public function index()
    {
        $appraisals = Appraisal::with(['employee', 'evaluator'])->paginate(15);
        $employees = Employee::active()->get();
        return view('performance::appraisals.index', compact('appraisals', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'review_period' => ['required', 'string', 'max:255'],
            'comments' => ['nullable', 'string'],
        ]);

        Appraisal::create(array_merge($validated, [
            'evaluator_id' => Auth::user()->employee->id ?? Auth::id(), // Fallback to user ID if no employee record
            'status' => 'pending',
        ]));

        return redirect()->route('performance.appraisals.index')
            ->with('success', 'Appraisal initiated successfully.');
    }

    public function update(Request $request, Appraisal $appraisal)
    {
        $validated = $request->validate([
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'comments' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,pending,completed'],
        ]);

        $appraisal->update($validated);

        return redirect()->route('performance.appraisals.index')
            ->with('success', 'Appraisal updated successfully.');
    }
}
