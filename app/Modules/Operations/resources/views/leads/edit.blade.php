<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('operations.leads.index') }}" class="btn btn-ghost btn-sm btn-square">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-xl font-bold">Edit Lead: {{ $lead->name }}</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Update lead information and status.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body">
                <form action="{{ route('operations.leads.update', $lead) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Contact Name*</span></label>
                            <input type="text" name="name" class="input input-bordered w-full" value="{{ old('name', $lead->name) }}" required />
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Company Name</span></label>
                            <input type="text" name="company_name" class="input input-bordered w-full" value="{{ old('company_name', $lead->company_name) }}" />
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Email Address</span></label>
                            <input type="email" name="email" class="input input-bordered w-full" value="{{ old('email', $lead->email) }}" />
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Phone Number</span></label>
                            <input type="text" name="phone" class="input input-bordered w-full" value="{{ old('phone', $lead->phone) }}" />
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Status*</span></label>
                            <select name="status" class="select select-bordered w-full">
                                @foreach(['new', 'contacted', 'qualified', 'lost', 'converted'] as $status)
                                    <option value="{{ $status }}" {{ old('status', $lead->status) === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-bold">Assigned To</span></label>
                            <select name="assigned_to" class="select select-bordered w-full">
                                <option value="">Unassigned</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('assigned_to', $lead->assigned_to) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-bold">Description / Notes</span></label>
                        <textarea name="description" class="textarea textarea-bordered h-24">{{ old('description', $lead->description) }}</textarea>
                    </div>

                    <div class="card-actions justify-between mt-6">
                        <form action="{{ route('operations.leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Delete this lead?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-ghost text-error">Delete Lead</button>
                        </form>
                        <button type="submit" class="btn btn-primary px-8">Update Lead</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
