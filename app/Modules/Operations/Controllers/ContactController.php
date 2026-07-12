<?php

namespace App\Modules\Operations\Controllers;

use App\Core\BaseController;
use App\Modules\Operations\Models\Contact;
use App\Modules\Operations\Models\Client;
use Illuminate\Http\Request;
use App\Modules\Operations\Requests\SaveContactRequest;
use App\Modules\Operations\DTOs\ContactData;
use App\Modules\Operations\Services\ContactService;

class ContactController extends BaseController
{
    public function __construct(
        protected ContactService $contactService
    ) {
        $this->authorizeResource(Contact::class, 'contact');
    }

    public function index()
    {
        $contacts = Contact::with('clients')
            ->where('tenant_id', saas_tenant('id'))
            ->latest()
            ->paginate(10);
        return view('operations::contacts.index', compact('contacts'));
    }

    public function create()
    {
        $clients = Client::where('tenant_id', saas_tenant('id'))->get();
        return view('operations::contacts.create', compact('clients'));
    }

    public function store(SaveContactRequest $request)
    {
        $dto = ContactData::fromArray($request->validated(), saas_tenant('id'));
        $this->contactService->createContact($dto);

        return redirect()->route('operations.contacts.index')->with('success', 'Contact created successfully.');
    }

    public function edit(Contact $contact)
    {
        $clients = Client::where('tenant_id', saas_tenant('id'))->get();
        $contact->load('clients');
        return view('operations::contacts.edit', compact('contact', 'clients'));
    }

    public function update(SaveContactRequest $request, Contact $contact)
    {
        $dto = ContactData::fromArray($request->validated(), saas_tenant('id'));
        $this->contactService->updateContact($contact, $dto);

        return redirect()->route('operations.contacts.index')->with('success', 'Contact updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        $this->contactService->deleteContact($contact);
        return redirect()->route('operations.contacts.index')->with('success', 'Contact deleted successfully.');
    }
}
