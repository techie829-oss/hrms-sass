<?php

namespace App\Modules\Operations\Controllers;

use App\Core\BaseController;
use App\Modules\Operations\Models\Lead;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;
use App\Modules\Operations\Requests\SaveLeadRequest;
use App\Modules\Operations\DTOs\LeadData;
use App\Modules\Operations\Services\LeadService;

class LeadController extends BaseController
{
    public function __construct(
        protected LeadService $leadService
    ) {
        $this->authorizeResource(Lead::class, 'lead');
    }

    public function index()
    {
        $leads = Lead::with('assignee')
            ->where('tenant_id', saas_tenant('id'))
            ->latest()
            ->paginate(10);
        return view('operations::leads.index', compact('leads'));
    }

    public function create()
    {
        $employees = Employee::where('tenant_id', saas_tenant('id'))->get();
        return view('operations::leads.create', compact('employees'));
    }

    public function store(SaveLeadRequest $request)
    {
        $dto = LeadData::fromArray($request->validated(), saas_tenant('id'));
        $this->leadService->createLead($dto);

        return redirect()->route('operations.leads.index')->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $lead->load('assignee', 'projects');
        return view('operations::leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $employees = Employee::where('tenant_id', saas_tenant('id'))->get();
        return view('operations::leads.edit', compact('lead', 'employees'));
    }

    public function update(SaveLeadRequest $request, Lead $lead)
    {
        $dto = LeadData::fromArray($request->validated(), saas_tenant('id'));
        $this->leadService->updateLead($lead, $dto);

        return redirect()->route('operations.leads.index')->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $this->leadService->deleteLead($lead);
        return redirect()->route('operations.leads.index')->with('success', 'Lead deleted successfully.');
    }
}
