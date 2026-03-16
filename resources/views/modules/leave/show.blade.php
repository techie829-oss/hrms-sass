<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('leave.requests.index') }}" class="btn btn-ghost btn-sm btn-square">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold">Leave Request</h2>
                    @php
                        $statusClasses = [
                            'pending' => 'badge-warning',
                            'approved' => 'badge-success',
                            'rejected' => 'badge-error',
                            'cancelled' => 'badge-ghost',
                        ];
                        $statusBadge = $statusClasses[$leaveRequest->status] ?? 'badge-ghost';
                    @endphp
                    <span class="badge {{ $statusBadge }} font-bold uppercase">{{ $leaveRequest->status }}</span>
                </div>
                <p class="text-sm opacity-70 mt-1">
                    {{ $leaveRequest->employee->first_name }} {{ $leaveRequest->employee->last_name }}
                    &mdash; {{ $leaveRequest->leaveType->name }}
                </p>
            </div>

            @if($leaveRequest->status === 'pending')
            <div class="ml-auto flex items-center gap-3">
                <form action="{{ route('leave.requests.status', $leaveRequest->id) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn btn-success">
                        <span class="material-symbols-outlined text-base">check_circle</span> Approve
                    </button>
                </form>
                <button onclick="document.getElementById('reject_modal').showModal()" class="btn btn-error">
                    <span class="material-symbols-outlined text-base">cancel</span> Reject
                </button>
            </div>
            @endif
        </div>
    </x-slot>

    <dialog id="reject_modal" class="modal">
        <div class="modal-box text-left max-w-sm">
            <h3 class="font-bold text-lg mb-4">Reject Leave Request</h3>
            <form action="{{ route('leave.requests.status', $leaveRequest->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <div class="form-control">
                    <label class="label font-bold" for="rejection_reason">Reason for rejection</label>
                    <textarea name="rejection_reason" class="textarea textarea-bordered w-full" rows="3" required placeholder="Enter reason..."></textarea>
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn btn-error">Reject Request</button>
                    <form method="dialog"><button class="btn btn-ghost">Cancel</button></form>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-0">
                    <div class="p-6 border-b border-base-200 bg-base-200/50">
                        <h3 class="text-sm font-bold uppercase tracking-widest">Leave Details</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Leave Type</p>
                            <p class="font-bold">{{ $leaveRequest->leaveType->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Start Date</p>
                            <p class="font-bold">{{ $leaveRequest->start_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">End Date</p>
                            <p class="font-bold">{{ $leaveRequest->end_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Total Days</p>
                            <p class="text-2xl font-bold text-primary">{{ $leaveRequest->total_days }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Half Day?</p>
                            <p class="font-semibold">{{ $leaveRequest->is_half_day ? 'Yes (' . ucfirst(str_replace('_', ' ', $leaveRequest->half_day_type ?? '')) . ')' : 'No' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-widest mb-3">Reason</h3>
                    <p class="text-sm leading-relaxed">{{ $leaveRequest->reason }}</p>
                </div>
            </div>

            @if($leaveRequest->rejection_reason)
            <div class="card bg-error/5 shadow-sm border border-error/20">
                <div class="card-body">
                    <h3 class="text-xs font-bold text-error uppercase tracking-widest mb-3">
                        <span class="material-symbols-outlined text-base align-middle">cancel</span>
                        Rejection Reason
                    </h3>
                    <p class="text-sm leading-relaxed">{{ $leaveRequest->rejection_reason }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-widest border-b border-base-200 pb-3 mb-4">Employee</h3>
                    <div class="flex items-center gap-4">
                        <div class="avatar placeholder">
                            <div class="bg-primary/10 text-primary rounded-xl w-12 h-12 font-bold text-sm">
                                {{ strtoupper(substr($leaveRequest->employee->first_name, 0, 1) . substr($leaveRequest->employee->last_name, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <div class="font-bold">{{ $leaveRequest->employee->first_name }} {{ $leaveRequest->employee->last_name }}</div>
                            <div class="text-xs opacity-60">{{ $leaveRequest->employee->employee_id }}</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('hr.employees.show', $leaveRequest->employee->id) }}" class="btn btn-sm btn-outline btn-primary w-full">View Profile</a>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-widest border-b border-base-200 pb-3 mb-4">Request Info</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Submitted</p>
                            <p class="text-sm font-semibold">{{ $leaveRequest->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Last Updated</p>
                            <p class="text-sm font-semibold">{{ $leaveRequest->updated_at->diffForHumans() }}</p>
                        </div>
                        @if($leaveRequest->approved_at)
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Reviewed At</p>
                            <p class="text-sm font-semibold">{{ $leaveRequest->approved_at->format('M d, Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
