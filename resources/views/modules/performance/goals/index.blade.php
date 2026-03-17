<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-on-surface">Employee Goals</h2>
            <div class="flex gap-2">
                <button onclick="goal_modal.showModal()" class="btn btn-sm btn-primary border-none rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm">
                    <span class="material-symbols-outlined text-sm">add_circle</span> Assign Goal
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Goals Grid (Matches Dashboard Refined Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($goals as $goal)
                <div class="card bg-surface-container-lowest shadow-sm border border-outline-variant/10 hover:border-primary/30 transition-all flex flex-col justify-between rounded-xl overflow-hidden min-h-[180px]">
                    <div class="card-body p-5">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="bg-primary/10 text-primary rounded-lg w-9 h-9 font-bold text-[10px] uppercase tracking-wider flex items-center justify-center">
                                        {{ substr($goal->employee->first_name, 0, 1) }}{{ substr($goal->employee->last_name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-bold text-on-surface leading-tight text-sm">{{ $goal->title }}</h3>
                                    <p class="text-[9px] uppercase tracking-widest font-bold text-on-surface-variant opacity-70 mt-0.5">{{ $goal->employee->full_name }}</p>
                                </div>
                            </div>
                            <span class="badge badge-sm font-bold text-[8px] h-auto px-2 py-0.5
                                @if($goal->status === 'completed') badge-success text-white
                                @elseif($goal->status === 'cancelled') badge-error text-white
                                @else badge-info text-white @endif">
                                {{ str_replace('_', ' ', strtoupper($goal->status)) }}
                            </span>
                        </div>
                        
                        <p class="text-[11px] font-medium text-on-surface-variant mb-5 leading-relaxed line-clamp-2">{{ $goal->description }}</p>

                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-1.5 px-0.5">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant opacity-60">Progress</span>
                                <span class="text-[11px] font-black text-primary">{{ $goal->progress_percentage }}%</span>
                            </div>
                            <progress class="progress progress-primary w-full h-1.5 rounded-full bg-surface-container-low" value="{{ $goal->progress_percentage }}" max="100"></progress>
                        </div>
                    </div>

                    <div class="px-5 py-3.5 border-t border-outline-variant/5 flex items-center justify-between mt-auto bg-surface-container-low/20">
                        <div class="flex items-center gap-4 text-[9px] font-bold uppercase tracking-wider text-on-surface-variant opacity-60 italic">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">calendar_today</span>
                                {{ $goal->start_date->format('M d') }}
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">event</span>
                                {{ $goal->end_date->format('M d') }}
                            </div>
                        </div>
                        <button onclick="document.getElementById('update_goal_{{ $goal->id }}').showModal()" class="text-[9px] font-bold text-primary uppercase tracking-widest hover:underline italic">Update →</button>
                    </div>

                    {{-- Update Goal Modal (Dense Layout) --}}
                    <dialog id="update_goal_{{ $goal->id }}" class="modal">
                        <div class="modal-box max-w-xl bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-2xl p-0 overflow-hidden text-left font-sans">
                            <div class="p-5 border-b border-outline-variant/5 flex items-center justify-between bg-surface-container-low/30">
                                <div>
                                    <h3 class="font-bold text-xs uppercase tracking-wider text-on-surface">Track Progress</h3>
                                    <p class="text-[9px] font-bold opacity-50 uppercase tracking-widest mt-0.5">{{ $goal->title }}</p>
                                </div>
                                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
                            </div>
                            
                            <form action="{{ route('performance.goals.update', $goal->id) }}" method="POST" class="p-6 space-y-6">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-control">
                                    <div class="flex justify-between items-center mb-1 bg-surface-container-low/50 p-2 rounded-lg">
                                        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Completion Level</span>
                                        <span id="prog_val_{{ $goal->id }}" class="text-xs font-black text-primary">{{ $goal->progress_percentage }}%</span>
                                    </div>
                                    <input name="progress_percentage" type="range" min="0" max="100" value="{{ $goal->progress_percentage }}" class="range range-primary range-xs" step="5" oninput="document.getElementById('prog_val_{{ $goal->id }}').innerText = this.value + '%'" />
                                    <div class="w-full flex justify-between text-[8px] px-1 mt-2 opacity-40 font-bold uppercase tracking-tighter">
                                        <span>0%</span><span>25%</span><span>50%</span><span>75%</span><span>100%</span>
                                    </div>
                                </div>

                                <div class="form-control">
                                    <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Outcome Status</span></label>
                                    <select name="status" class="select select-sm select-bordered focus:select-primary rounded-lg text-xs font-bold">
                                        <option value="in_progress" {{ $goal->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $goal->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $goal->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>

                                <div class="pt-2">
                                    <button type="submit" class="btn btn-primary btn-sm w-full rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm">Commit Progress Update</button>
                                </div>
                            </form>
                        </div>
                        <form method="dialog" class="modal-backdrop bg-on-surface/20 backdrop-blur-[2px]"><button>close</button></form>
                    </dialog>
                </div>
            @empty
                <div class="col-span-full py-16 text-center border-2 border-dashed border-outline-variant/20 rounded-xl bg-surface-container-low/10">
                    <div class="flex flex-col items-center gap-3 opacity-30">
                        <span class="material-symbols-outlined text-4xl">target</span>
                        <p class="text-xs font-bold uppercase tracking-widest">No strategic targets defined.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($goals->hasPages())
        <div class="mt-4 bg-surface-container-lowest p-3 rounded-xl border border-outline-variant/10">
            {{ $goals->links() }}
        </div>
        @endif
    </div>

    {{-- Create Goal Modal (Matches Refined Style) --}}
    <dialog id="goal_modal" class="modal">
        <div class="modal-box max-w-xl bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-2xl p-0 overflow-hidden text-left font-sans">
            <div class="p-5 border-b border-outline-variant/5 flex items-center justify-between bg-surface-container-low/30">
                <div>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-on-surface">Assign New Goal</h3>
                    <p class="text-[9px] font-bold opacity-50 uppercase tracking-widest mt-0.5">Define strategic performance target</p>
                </div>
                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
            </div>
            
            <form action="{{ route('performance.goals.store') }}" method="POST" class="p-6 space-y-5 text-left">
                @csrf
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Select Employee</span></label>
                    <select name="employee_id" required class="select select-sm select-bordered focus:select-primary rounded-lg text-xs font-bold">
                        <option value="" disabled selected>Target individual...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Goal Title</span></label>
                    <input type="text" name="title" required class="input input-sm input-bordered focus:input-primary rounded-lg text-xs" placeholder="e.g. Q1 Sales Targets, AWS Certification" />
                </div>
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Description</span></label>
                    <textarea name="description" class="textarea textarea-bordered focus:textarea-primary rounded-lg text-xs" rows="3" placeholder="Define KPIs and success metrics..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Start Cycle</span></label>
                        <input type="date" name="start_date" required class="input input-sm input-bordered focus:input-primary rounded-lg text-xs" />
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Target Completion</span></label>
                        <input type="date" name="end_date" required class="input input-sm input-bordered focus:input-primary rounded-lg text-xs" />
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit" class="btn btn-primary btn-sm w-full rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm shadow-primary/20">Initialize Strategic Goal</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-on-surface/20 backdrop-blur-[2px]"><button>close</button></form>
    </dialog>
</x-app-layout>
