<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Project') }}
        </h2>
    </x-slot>

    <div class="container mx-auto p-6 max-w-4xl">
        <div class="flex items-center gap-2 mb-6">
            <a href="{{ route('operations.projects.index') }}" class="btn btn-sm btn-ghost btn-square">
                <span class="material-symbols-outlined text-base">arrow_back</span>
            </a>
            <h1 class="text-2xl font-bold">New Project</h1>
        </div>

        <div class="card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body">
                <form action="{{ route('operations.projects.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control md:col-span-2">
                            <label class="label"><span class="label-text font-bold">Project Name</span></label>
                            <input type="text" name="name" class="input input-bordered w-full" required placeholder="Enter project title">
                        </div>

                        <div class="form-control md:col-span-2">
                            <label class="label"><span class="label-text font-bold">Description</span></label>
                            <x-editor name="description" placeholder="Describe project scope and goals..." />
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Client</span></label>
                            <x-rich-select name="client_id" placeholder="Internal Project">
                                <option value="">Internal Project</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </x-rich-select>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Status</span></label>
                            <select name="status" class="select select-bordered w-full">
                                <option value="planning">Planning</option>
                                <option value="in_progress">In Progress</option>
                                <option value="on_hold">On Hold</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Start Date</span></label>
                            <input type="date" name="start_date" class="input input-bordered w-full">
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Deadline</span></label>
                            <input type="date" name="deadline" class="input input-bordered w-full">
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Budget</span></label>
                            <div class="join w-full">
                                <span class="btn join-item pointer-events-none">$</span>
                                <input type="number" name="budget" step="0.01" class="input input-bordered join-item w-full" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="card-actions justify-end mt-10">
                        <a href="{{ route('operations.projects.index') }}" class="btn btn-ghost">Cancel</a>
                        <button type="submit" class="btn btn-primary px-8">Create Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
