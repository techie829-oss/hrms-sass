<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Employee Goals</h1>
                    <p class="opacity-70 mt-1.5 font-medium">Track individual progress and strategic targets.</p>
                </div>
                <button onclick="goal_modal.showModal()" class="btn btn-primary btn-sm flex items-center gap-2 px-5">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Assign Goal
                </button>
            </div>

            <!-- Goals Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($goals as $goal)
                    <div class="card bg-base-100 border border-base-200 shadow-sm transition-shadow hover:shadow-md h-fit">
                        <div class="card-body p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-accent/10 text-accent w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs">
                                            <span>{{ substr($goal->employee->first_name, 0, 1) }}{{ substr($goal->employee->last_name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="font-bold leading-tight">{{ $goal->title }}</h3>
                                        <p class="text-[10px] uppercase tracking-widest font-bold opacity-70">{{ $goal->employee->full_name }}</p>
                                    </div>
                                </div>
                                <span class="badge badge-sm font-bold
                                    @if($goal->status === 'completed') badge-success
                                    @elseif($goal->status === 'cancelled') badge-error
                                    @else badge-info @endif">
                                    {{ str_replace('_', ' ', ucfirst($goal->status)) }}
                                </span>
                            </div>
                            
                            <p class="text-sm opacity-80 mb-6 leading-relaxed line-clamp-2">{{ $goal->description }}</p>

                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-[11px] font-bold uppercase tracking-wider opacity-70">Progress</span>
                                    <span class="text-sm font-bold text-primary">{{ $goal->progress_percentage }}%</span>
                                </div>
                                <progress class="progress progress-primary w-full h-2 rounded-full bg-base-200" value="{{ $goal->progress_percentage }}" max="100"></progress>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-base-200 mt-2">
                                <div class="flex items-center gap-4 text-[11px] font-bold uppercase tracking-wider opacity-70">
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                        {{ $goal->start_date->format('M d, Y') }}
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">event</span>
                                        {{ $goal->end_date->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button class="btn btn-ghost btn-xs text-primary font-bold">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center card bg-base-100 border border-dashed border-base-300">
                        <div class="flex flex-col items-center gap-3 opacity-50">
                            <span class="material-symbols-outlined text-[48px]">target</span>
                            <p class="font-medium italic">No employee goals assigned yet.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            <!-- Pagination -->
            <div class="mt-8">
                {{ $goals->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <dialog id="goal_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-base-100 border border-base-200 max-w-lg">
            <h3 class="font-bold text-lg mb-6">Assign New Goal</h3>
            <form action="{{ route('performance.goals.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-xs uppercase tracking-widest">Select Employee</span></label>
                    <select name="employee_id" required class="select select-bordered w-full">
                        <option value="" disabled selected>Select an employee...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-xs uppercase tracking-widest">Goal Title</span></label>
                    <input type="text" name="title" required class="input input-bordered w-full" placeholder="e.g., Increase Quarterly Revenue, Complete Certification">
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-xs uppercase tracking-widest">Description</span></label>
                    <textarea name="description" class="textarea textarea-bordered w-full" rows="3" placeholder="Detailed goal breakdown..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-bold text-xs uppercase tracking-widest">Start Date</span></label>
                        <input type="date" name="start_date" required class="input input-bordered w-full">
                    </div>
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-bold text-xs uppercase tracking-widest">End Date</span></label>
                        <input type="date" name="end_date" required class="input input-bordered w-full">
                    </div>
                </div>
                <div class="modal-action mt-8 pt-6 border-t border-base-200">
                    <button type="button" onclick="goal_modal.close()" class="btn btn-ghost btn-sm font-bold uppercase tracking-widest">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-8 font-bold uppercase tracking-widest">Assign Goal</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</x-app-layout>
