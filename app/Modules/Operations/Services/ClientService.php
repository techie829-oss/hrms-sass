<?php

namespace App\Modules\Operations\Services;

use App\Modules\Operations\Models\Client;
use App\Modules\Operations\DTOs\ClientData;

class ClientService
{
    public function createClient(ClientData $data): Client
    {
        return Client::create([
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'company' => $data->company,
            'address' => $data->address,
            'tenant_id' => $data->tenant_id,
        ]);
    }
}
