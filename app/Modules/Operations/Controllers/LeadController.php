<?php

namespace App\Modules\Operations\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Operations\Models\Lead;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::with('assignee')
            ->where('tenant_id', tenant('id'))
            ->latest()
            ->paginate(10);
        return view('operations::leads.index', compact('leads'));
    }

    public function create()
    {
        $employees = Employee::where('tenant_id', tenant('id'))->get();
        return view('operations::leads.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'status' => 'required|string|in:new,contacted,qualified,lost,converted',
            'assigned_to' => 'nullable|exists:employees,id',
            'description' => 'nullable|string',
        ]);

        $validated['tenant_id'] = tenant('id');
        Lead::create($validated);

        return redirect()->route('operations.leads.index')->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $lead->load('assignee', 'projects');
        return view('operations::leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $employees = Employee::where('tenant_id', tenant('id'))->get();
        return view('operations::leads.edit', compact('lead', 'employees'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'status' => 'required|string|in:new,contacted,qualified,lost,converted',
            'assigned_to' => 'nullable|exists:employees,id',
            'description' => 'nullable|string',
        ]);

        $lead->update($validated);

        return redirect()->route('operations.leads.index')->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('operations.leads.index')->with('success', 'Lead deleted successfully.');
    }
}
