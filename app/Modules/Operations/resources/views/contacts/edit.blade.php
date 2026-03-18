<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('operations.contacts.index') }}" class="btn btn-ghost btn-sm btn-square">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-xl font-bold">Edit Contact: {{ $contact->name }}</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Update contact person details and account links.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body">
                <form action="{{ route('operations.contacts.update', $contact) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Contact Name*</span></label>
                            <input type="text" name="name" class="input input-bordered w-full" value="{{ old('name', $contact->name) }}" required />
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Job Title</span></label>
                            <input type="text" name="job_title" class="input input-bordered w-full" value="{{ old('job_title', $contact->job_title) }}" />
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Email Address</span></label>
                            <input type="email" name="email" class="input input-bordered w-full" value="{{ old('email', $contact->email) }}" />
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Phone Number</span></label>
                            <input type="text" name="phone" class="input input-bordered w-full" value="{{ old('phone', $contact->phone) }}" />
                        </div>

                        <div class="form-control w-full md:col-span-2">
                            <label class="label"><span class="label-text font-bold">Linked Clients / Accounts</span></label>
                            <select name="client_ids[]" class="select select-bordered w-full h-auto py-2" multiple size="5">
                                @php $currentClientIds = $contact->clients->pluck('id')->toArray(); @endphp
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ in_array($client->id, old('client_ids', $currentClientIds)) ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ $client->company }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] mt-1 opacity-60">Hold Ctrl/Cmd to select multiple accounts.</p>
                        </div>
                    </div>

                    <div class="card-actions justify-end mt-6">
                        <button type="submit" class="btn btn-primary px-8">Update Contact</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
