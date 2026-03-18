<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('operations.leads.index') }}" class="btn btn-ghost btn-sm btn-square">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-xl font-bold">Create New Lead</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Enter detail to capture a new business opportunity.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body">
                <form action="{{ route('operations.leads.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Info -->
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Contact Name*</span></label>
                            <input type="text" name="name" class="input input-bordered w-full" placeholder="Full name" required value="{{ old('name') }}" />
                            @error('name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Company Name</span></label>
                            <input type="text" name="company_name" class="input input-bordered w-full" placeholder="Organization name" value="{{ old('company_name') }}" />
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Email Address</span></label>
                            <input type="email" name="email" class="input input-bordered w-full" placeholder="email@example.com" value="{{ old('email') }}" />
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Phone Number</span></label>
                            <input type="text" name="phone" class="input input-bordered w-full" placeholder="+1..." value="{{ old('phone') }}" />
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Status*</span></label>
                            <select name="status" class="select select-bordered w-full">
                                <option value="new" selected>New</option>
                                <option value="contacted">Contacted</option>
                                <option value="qualified">Qualified</option>
                                <option value="lost">Lost</option>
                                <option value="converted">Converted</option>
                            </select>
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Assigned To</span></label>
                            <select name="assigned_to" class="select select-bordered w-full">
                                <option value="">Unassigned</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->id }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Lead Source</span></label>
                            <input type="text" name="source" class="input input-bordered w-full" placeholder="Web, Referral, etc." value="{{ old('source') }}" />
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-bold">Description / Notes</span></label>
                        <textarea name="description" class="textarea textarea-bordered h-24" placeholder="Additional details about the lead...">{{ old('description') }}</textarea>
                    </div>

                    <div class="card-actions justify-end mt-6">
                        <button type="submit" class="btn btn-primary px-8">Create Lead</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
