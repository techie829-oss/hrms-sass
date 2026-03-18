<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('operations.tasks.index') }}" class="btn btn-ghost btn-sm btn-square">
                <span class="material-symbols-outlined text-base">arrow_back</span>
            </a>
            <div>
                <h2 class="text-xl font-bold uppercase tracking-tight text-primary">Assign New Task</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Create a new task and assign it to a team member.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-8">
                <form action="{{ route('operations.tasks.store') }}" method="POST">
                    @csrf
                    <div class="form-control mb-6">
                        <label class="label text-xs uppercase font-bold text-base-content/50">Task Summary*</label>
                        <input type="text" name="title" class="input input-bordered w-full h-12 text-base font-medium focus:ring-2 focus:ring-primary/20" required placeholder="What needs to be done?">
                        <label class="label"><span class="label-text-alt text-base-content/40 italic">Briefly describe the task.</span></label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 font-display">
                        <div class="form-control">
                            <label class="label text-xs uppercase font-bold text-base-content/50">Select Project (Optional)</label>
                            <select name="project_id" class="select select-bordered w-full">
                                <option value="">Standalone / No Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label text-xs uppercase font-bold text-base-content/50">Assign To (Optional)</label>
                            <select name="assigned_to" class="select select-bordered w-full">
                                <option value="">Unassigned</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="form-control">
                            <label class="label text-xs uppercase font-bold text-base-content/50">Priority*</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['low', 'medium', 'high', 'urgent'] as $prio)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="priority" value="{{ $prio }}" class="peer hidden" {{ $prio === 'medium' ? 'checked' : '' }}>
                                        <div class="badge badge-lg border-2 border-base-200 bg-base-100 text-[10px] font-bold uppercase py-4 px-4 peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary transition-all">
                                            {{ $prio }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-control">
                            <label class="label text-xs uppercase font-bold text-base-content/50">Due Date</label>
                            <input type="date" name="due_date" class="input input-bordered w-full">
                        </div>
                    </div>

                    <div class="form-control mb-8">
                        <label class="label text-xs uppercase font-bold text-base-content/50">Task Description</label>
                        <x-editor name="description" placeholder="Provide detailed instructions or context for this task..." />
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-base-200">
                        <a href="{{ route('operations.tasks.index') }}" class="btn btn-ghost">{{ __('Cancel') }}</a>
                        <button type="submit" class="btn btn-primary px-8">
                            <span class="material-symbols-outlined text-base">save</span>
                            {{ __('Create Task') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
