<?php

namespace App\Modules\Operations\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Operations\Models\Contact;
use App\Modules\Operations\Models\Client;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::with('clients')
            ->where('tenant_id', tenant('id'))
            ->latest()
            ->paginate(10);
        return view('operations::contacts.index', compact('contacts'));
    }

    public function create()
    {
        $clients = Client::where('tenant_id', tenant('id'))->get();
        return view('operations::contacts.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'job_title' => 'nullable|string|max:255',
            'client_ids' => 'nullable|array',
            'client_ids.*' => 'exists:clients,id',
        ]);

        $validated['tenant_id'] = tenant('id');
        $contact = Contact::create($validated);

        if ($request->has('client_ids')) {
            $contact->clients()->sync($request->client_ids);
        }

        return redirect()->route('operations.contacts.index')->with('success', 'Contact created successfully.');
    }

    public function edit(Contact $contact)
    {
        $clients = Client::where('tenant_id', tenant('id'))->get();
        $contact->load('clients');
        return view('operations::contacts.edit', compact('contact', 'clients'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'job_title' => 'nullable|string|max:255',
            'client_ids' => 'nullable|array',
            'client_ids.*' => 'exists:clients,id',
        ]);

        $contact->update($validated);

        if ($request->has('client_ids')) {
            $contact->clients()->sync($request->client_ids);
        }

        return redirect()->route('operations.contacts.index')->with('success', 'Contact updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('operations.contacts.index')->with('success', 'Contact deleted successfully.');
    }
}
