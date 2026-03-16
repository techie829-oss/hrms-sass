<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Performance Appraisals</h1>
                    <p class="opacity-70 mt-1.5 font-medium">Manage and review employee performance cycles.</p>
                </div>
                <button onclick="appraisal_modal.showModal()" class="btn btn-primary btn-sm flex items-center gap-2 px-5">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Initiate Appraisal
                </button>
            </div>

            <!-- Appraisals Table -->
            <div class="card bg-base-100 border border-base-200 shadow-sm overflow-hidden min-h-[400px]">
                <div class="overflow-x-auto">
                    <table class="table table-md">
                        <thead class="bg-base-200/50">
                            <tr>
                                <th class="font-bold text-[11px] uppercase tracking-wider py-4 opacity-70">Employee</th>
                                <th class="font-bold text-[11px] uppercase tracking-wider py-4 opacity-70">Period</th>
                                <th class="font-bold text-[11px] uppercase tracking-wider py-4 text-center opacity-70">Score</th>
                                <th class="font-bold text-[11px] uppercase tracking-wider py-4 text-center opacity-70">Status</th>
                                <th class="font-bold text-[11px] uppercase tracking-wider py-4 text-right opacity-70">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appraisals as $appraisal)
                                <tr class="hover:bg-base-200/50 transition-colors border-b border-base-200">
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="avatar placeholder">
                                                <div class="bg-primary/10 text-primary w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs">
                                                    <span>{{ substr($appraisal->employee->first_name, 0, 1) }}{{ substr($appraisal->employee->last_name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-semibold">{{ $appraisal->employee->full_name }}</div>
                                                <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Evaluator: {{ $appraisal->evaluator->full_name ?? 'System' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 font-medium opacity-80">{{ $appraisal->review_period }}</td>
                                    <td class="text-center py-4">
                                        @if($appraisal->score)
                                            <span class="font-bold text-sm {{ $appraisal->score >= 80 ? 'text-success' : ($appraisal->score >= 50 ? 'text-warning' : 'text-error') }}">
                                                {{ number_format($appraisal->score, 1) }}%
                                            </span>
                                        @else
                                            <span class="font-medium text-xs opacity-50">--</span>
                                        @endif
                                    </td>
                                    <td class="text-center py-4">
                                        <span class="badge badge-sm font-bold
                                            @if($appraisal->status === 'completed') badge-success
                                            @elseif($appraisal->status === 'pending') badge-warning
                                            @else badge-ghost @endif">
                                            {{ ucfirst($appraisal->status) }}
                                        </span>
                                    </td>
                                    <td class="text-right py-4">
                                        <button class="btn btn-ghost btn-xs text-primary font-bold">Review</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center italic font-medium opacity-50">No appraisals found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-base-200 bg-base-200/30">
                    {{ $appraisals->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <dialog id="appraisal_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-base-100 border border-base-200 max-w-lg">
            <h3 class="font-bold text-lg mb-6">Initiate Appraisal</h3>
            <form action="{{ route('performance.appraisals.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-xs uppercase tracking-widest">Select Employee</span></label>
                    <select name="employee_id" required class="select select-bordered w-full">
                        <option value="" disabled selected>Select an employee...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }} ({{ $emp->employee_id }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-xs uppercase tracking-widest">Review Period</span></label>
                    <input type="text" name="review_period" required class="input input-bordered w-full" placeholder="e.g., Annual Review 2026, Q1 Review">
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-xs uppercase tracking-widest">Preliminary Comments</span></label>
                    <textarea name="comments" class="textarea textarea-bordered w-full" rows="3" placeholder="Any initial notes..."></textarea>
                </div>
                <div class="modal-action mt-8 pt-6 border-t border-base-200">
                    <button type="button" onclick="appraisal_modal.close()" class="btn btn-ghost btn-sm font-bold uppercase tracking-widest">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-8 font-bold uppercase tracking-widest">Start Review</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</x-app-layout>
