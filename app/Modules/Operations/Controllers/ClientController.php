<?php

namespace App\Modules\Operations\Controllers;

use App\Core\BaseController;
use App\Modules\Operations\Models\Client;
use Illuminate\Http\Request;
use App\Modules\Operations\Requests\StoreClientRequest;
use App\Modules\Operations\DTOs\ClientData;
use App\Modules\Operations\Services\ClientService;

class ClientController extends BaseController
{
    public function __construct(
        protected ClientService $clientService
    ) {
        $this->authorizeResource(Client::class, 'client');
    }

    public function index()
    {
        $clients = Client::where('tenant_id', saas_tenant('id'))
            ->with(['contacts'])
            ->withCount('projects')
            ->latest()
            ->paginate(10);

        return view('operations::clients.index', compact('clients'));
    }

    public function store(StoreClientRequest $request)
    {
        $dto = ClientData::fromArray($request->validated(), saas_tenant('id'));
        $this->clientService->createClient($dto);

        return back()->with('success', 'Client added successfully.');
    }
}
