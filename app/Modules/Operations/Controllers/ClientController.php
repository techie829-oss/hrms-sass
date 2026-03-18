<?php

namespace App\Modules\Operations\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Operations\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::where('tenant_id', tenant('id'))
            ->with(['contacts'])
            ->withCount('projects')
            ->latest()
            ->paginate(10);

        return view('operations::clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $validated['tenant_id'] = tenant('id');
        Client::create($validated);

        return back()->with('success', 'Client added successfully.');
    }
}
