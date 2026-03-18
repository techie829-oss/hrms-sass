<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('operations.tasks.index') }}" class="btn btn-ghost btn-sm btn-square">
                <span class="material-symbols-outlined text-base">arrow_back</span>
            </a>
            <div>
                <h2 class="text-xl font-bold uppercase tracking-tight text-primary">Edit Task</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Update task details and monitor progress.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-8">
                    <form action="{{ route('operations.tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-control mb-6">
                            <label class="label text-xs uppercase font-bold text-base-content/50">Task Summary*</label>
                            <input type="text" name="title" value="{{ $task->title }}" class="input input-bordered w-full h-12 text-base font-medium focus:ring-2 focus:ring-primary/20" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="form-control">
                                <label class="label text-xs uppercase font-bold text-base-content/50">Project</label>
                                <select name="project_id" class="select select-bordered w-full">
                                    <option value="">Standalone / No Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ $task->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-control">
                                <label class="label text-xs uppercase font-bold text-base-content/50">Assign To</label>
                                <select name="assigned_to" class="select select-bordered w-full">
                                    <option value="">Unassigned</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ $task->assigned_to == $employee->id ? 'selected' : '' }}>{{ $employee->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="form-control">
                                <label class="label text-xs uppercase font-bold text-base-content/50">Due Date</label>
                                <input type="date" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}" class="input input-bordered w-full">
                            </div>
                            <div class="form-control">
                                <label class="label text-xs uppercase font-bold text-base-content/50">Priority</label>
                                <select name="priority" class="select select-bordered w-full">
                                    <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ $task->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-control mb-8">
                            <label class="label text-xs uppercase font-bold text-base-content/50">Task Description</label>
                            <x-editor name="description" :value="$task->description" />
                        </div>

                        <div class="flex justify-between items-center pt-6 border-t border-base-200">
                             <button type="button" onclick="delete_task_modal.showModal()" class="btn btn-ghost text-error btn-sm font-bold uppercase tracking-wider">
                                <span class="material-symbols-outlined text-base">delete</span>
                                Delete Task
                            </button>
                            <div class="flex gap-3">
                                <a href="{{ route('operations.tasks.index') }}" class="btn btn-ghost">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-primary px-8">
                                    <span class="material-symbols-outlined text-base">save</span>
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Meta Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="font-bold text-xs uppercase text-base-content/50 mb-4">Current Status</h3>
                    <form action="{{ route('operations.tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            @foreach(['todo' => 'To Do', 'in_progress' => 'In Progress', 'review' => 'Review', 'done' => 'Completed'] as $val => $label)
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-base-200 hover:bg-base-200/50 cursor-pointer transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5 group">
                                    <input type="radio" name="status" value="{{ $val }}" class="radio radio-primary radio-sm peer" {{ $task->status === $val ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span class="text-sm font-medium group-hover:text-primary transition-colors">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>

            <!-- Task Info -->
            <div class="card bg-base-200 shadow-sm">
                <div class="card-body p-6">
                    <div class="space-y-4">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-base-content/40 block mb-1">Created At</span>
                            <span class="text-xs font-medium">{{ $task->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        @if($task->completed_at && $task->status === 'done')
                            <div>
                                <span class="text-[10px] uppercase font-bold text-base-content/40 block mb-1">Completed At</span>
                                <span class="text-xs font-medium text-success">{{ $task->completed_at->format('M d, Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <dialog id="delete_task_modal" class="modal">
      <div class="modal-box max-w-sm text-center">
        <h3 class="font-bold text-lg text-error">Delete Task?</h3>
        <p class="py-4 opacity-70">This action cannot be undone. Are you sure you want to permanently delete this task?</p>
        <div class="modal-action flex justify-center gap-4">
            <form method="dialog"><button class="btn btn-sm btn-ghost">Cancel</button></form>
            <form action="{{ route('operations.tasks.destroy', $task) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-error px-6">Confirm Delete</button>
            </form>
        </div>
      </div>
    </dialog>
</x-app-layout>
