<?php

namespace App\Modules\Operations\Services;

use App\Modules\Operations\Models\Contact;
use App\Modules\Operations\DTOs\ContactData;
use Illuminate\Support\Facades\DB;

class ContactService
{
    public function createContact(ContactData $data): Contact
    {
        return DB::transaction(function () use ($data) {
            $contact = Contact::create([
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'job_title' => $data->job_title,
                'tenant_id' => $data->tenant_id,
            ]);

            if ($data->client_ids !== null) {
                $contact->clients()->sync($data->client_ids);
            }

            return $contact;
        });
    }

    public function updateContact(Contact $contact, ContactData $data): Contact
    {
        return DB::transaction(function () use ($contact, $data) {
            $contact->update([
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'job_title' => $data->job_title,
            ]);

            if ($data->client_ids !== null) {
                $contact->clients()->sync($data->client_ids);
            }

            return $contact;
        });
    }

    public function deleteContact(Contact $contact): void
    {
        $contact->delete();
    }
}
