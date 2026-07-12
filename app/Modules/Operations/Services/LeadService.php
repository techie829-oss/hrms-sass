<?php

namespace App\Modules\Operations\Services;

use App\Modules\Operations\Models\Lead;
use App\Modules\Operations\DTOs\LeadData;

class LeadService
{
    public function createLead(LeadData $data): Lead
    {
        return Lead::create([
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'company_name' => $data->company_name,
            'source' => $data->source,
            'status' => $data->status,
            'assigned_to' => $data->assigned_to,
            'description' => $data->description,
            'tenant_id' => $data->tenant_id,
        ]);
    }

    public function updateLead(Lead $lead, LeadData $data): Lead
    {
        $lead->update([
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'company_name' => $data->company_name,
            'source' => $data->source,
            'status' => $data->status,
            'assigned_to' => $data->assigned_to,
            'description' => $data->description,
        ]);

        return $lead;
    }

    public function deleteLead(Lead $lead): void
    {
        $lead->delete();
    }
}
