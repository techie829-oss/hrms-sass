<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-on-surface">Performance Appraisals</h2>
            <div class="flex gap-2">
                <button onclick="appraisal_modal.showModal()" class="btn btn-sm btn-primary border-none rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm">
                    <span class="material-symbols-outlined text-sm">add_circle</span> Initiate Appraisal
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Appraisals Table (Matches Dashboard List) -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-outline-variant/5 flex items-center justify-between">
                <h3 class="font-bold text-xs uppercase tracking-wider text-on-surface">Appraisal Records</h3>
                <div class="flex items-center gap-2">
                    <span class="text-[9px] font-bold text-on-surface-variant uppercase tracking-wider italic">Review cycle 2026</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-xs table-zebra w-full text-[11px]">
                    <thead>
                        <tr class="text-on-surface-variant/70 border-b border-outline-variant/5">
                            <th class="py-3 px-5">Employee</th>
                            <th>Review Period</th>
                            <th class="text-center">Score</th>
                            <th class="text-center">Status</th>
                            <th class="text-right pr-5">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="font-medium">
                        @forelse($appraisals as $appraisal)
                        <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                            <td class="py-3 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-primary/10 text-primary rounded-lg w-8 h-8 font-bold text-[9px]">
                                            {{ substr($appraisal->employee->first_name, 0, 1) }}{{ substr($appraisal->employee->last_name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold text-on-surface">{{ $appraisal->employee->full_name }}</div>
                                        <div class="text-[9px] opacity-60">Evaluator: {{ $appraisal->evaluator->full_name ?? 'System' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $appraisal->review_period }}</td>
                            <td class="text-center">
                                @if($appraisal->score)
                                    <span class="font-bold {{ $appraisal->score >= 80 ? 'text-success' : ($appraisal->score >= 50 ? 'text-warning' : 'text-error') }}">
                                        {{ number_format($appraisal->score, 1) }}%
                                    </span>
                                @else
                                    <span class="opacity-30">--</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($appraisal->status === 'completed')
                                    <span class="badge badge-success badge-sm text-[8px] font-bold text-white px-2 py-0.5 h-auto">COMPLETED</span>
                                @elseif($appraisal->status === 'pending')
                                    <span class="badge badge-warning badge-sm text-[8px] font-bold text-white px-2 py-0.5 h-auto">PENDING</span>
                                @else
                                    <span class="badge badge-neutral badge-sm text-[8px] font-bold text-white px-2 py-0.5 h-auto uppercase">{{ $appraisal->status }}</span>
                                @endif
                            </td>
                            <td class="text-right pr-5">
                                <button onclick="document.getElementById('review_appraisal_{{ $appraisal->id }}').showModal()" class="btn btn-ghost btn-xs rounded-md text-primary font-bold italic">
                                    Review →
                                </button>
                            </td>
                        </tr>

                        {{-- Review Appraisal Modal (Refined) --}}
                        <dialog id="review_appraisal_{{ $appraisal->id }}" class="modal">
                            <div class="modal-box max-w-xl bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-2xl p-0 overflow-hidden text-left font-sans">
                                <div class="p-5 border-b border-outline-variant/5 flex items-center justify-between bg-surface-container-low/30">
                                    <div>
                                        <h3 class="font-bold text-xs uppercase tracking-wider text-on-surface">Performance Review</h3>
                                        <p class="text-[9px] font-bold opacity-50 uppercase tracking-widest mt-0.5">{{ $appraisal->employee->full_name }} — {{ $appraisal->review_period }}</p>
                                    </div>
                                    <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
                                </div>
                                
                                <form action="{{ route('performance.appraisals.update', $appraisal->id) }}" method="POST" class="p-6 space-y-5">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="form-control">
                                        <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Final Score (0-100)</span></label>
                                        <div class="relative">
                                            <input name="score" type="number" step="0.1" min="0" max="100" value="{{ $appraisal->score ?? '' }}" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs font-bold" required />
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 font-black text-primary text-[10px] opacity-40">%</span>
                                        </div>
                                    </div>

                                    <div class="form-control">
                                        <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Final Comments / Feedback</span></label>
                                        <textarea name="comments" class="textarea textarea-bordered focus:textarea-primary rounded-lg text-xs" rows="4">{{ $appraisal->comments }}</textarea>
                                    </div>

                                    <div class="form-control">
                                        <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Decision Status</span></label>
                                        <div class="flex gap-3 mt-1">
                                            <label class="flex items-center gap-2 cursor-pointer bg-surface-container-low px-4 py-2 rounded-lg border border-outline-variant/5 transition-all hover:bg-surface-container-low/60">
                                                <input type="radio" name="status" value="pending" class="radio radio-xs radio-primary" {{ $appraisal->status === 'pending' ? 'checked' : '' }} />
                                                <span class="text-[10px] font-bold opacity-70">PENDING</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer bg-surface-container-low px-4 py-2 rounded-lg border border-outline-variant/5 transition-all hover:bg-surface-container-low/60">
                                                <input type="radio" name="status" value="completed" class="radio radio-xs radio-success" {{ $appraisal->status === 'completed' ? 'checked' : '' }} />
                                                <span class="text-[10px] font-bold text-success opacity-80">COMPLETED</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="pt-2">
                                        <button type="submit" class="btn btn-primary btn-sm w-full rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm">Commit Review</button>
                                    </div>
                                </form>
                            </div>
                            <form method="dialog" class="modal-backdrop bg-on-surface/20 backdrop-blur-[2px]"><button>close</button></form>
                        </dialog>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-on-surface-variant opacity-70 italic">No appraisal records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-outline-variant/5 bg-surface-container-low/20">
                {{ $appraisals->links() }}
            </div>
        </div>
    </div>

    {{-- Create Appraisal Modal (Matches Refined Style) --}}
    <dialog id="appraisal_modal" class="modal">
        <div class="modal-box max-w-xl bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-2xl p-0 overflow-hidden text-left font-sans">
            <div class="p-5 border-b border-outline-variant/5 flex items-center justify-between bg-surface-container-low/30">
                <div>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-on-surface">Initiate Appraisal</h3>
                    <p class="text-[9px] font-bold opacity-50 uppercase tracking-widest mt-0.5">Start a new evaluation cycle</p>
                </div>
                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
            </div>
            
            <form action="{{ route('performance.appraisals.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Select Employee</span></label>
                    <select name="employee_id" required class="select select-sm select-bordered focus:select-primary rounded-lg text-xs">
                        <option value="" disabled selected>Choose evaluation target...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }} ({{ $emp->employee_id }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Review Period</span></label>
                    <input type="text" name="review_period" required class="input input-sm input-bordered focus:input-primary rounded-lg text-xs" placeholder="e.g. Annual Review 2026, Q2 Review" />
                </div>

                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Initial Comments</span></label>
                    <textarea name="comments" class="textarea textarea-bordered focus:textarea-primary rounded-lg text-xs" rows="3" placeholder="Scope or initial feedback..."></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-primary btn-sm w-full rounded-lg font-bold text-[10px] uppercase tracking-wider">Initialize Review</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-on-surface/20 backdrop-blur-[2px]"><button>close</button></form>
    </dialog>
</x-app-layout>
