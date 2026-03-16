<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Leave Management</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Manage employee leave requests and balances.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('leave.types.index') }}" class="btn btn-ghost btn-sm btn-outline">
                    <span class="material-symbols-outlined text-base">settings</span> Policies
                </a>
                <a href="{{ route('leave.requests.create') }}" class="btn btn-primary btn-sm">
                    <span class="material-symbols-outlined text-base">add</span> New Request
                </a>
            </div>
        </div>
    </x-slot>

    <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">
        <x-table :headers="['Employee', 'Leave Type', 'Start Date', 'End Date', 'Days', 'Status', 'Actions']" :striped="false">
            @forelse($requests as $request)
                <tr class="hover:bg-base-200/50 transition-colors border-b border-base-200">
                    <td class="py-3 px-6">
                        <div class="flex items-center gap-3">
                            <div class="avatar placeholder">
                                <div class="bg-primary/10 text-primary rounded-lg w-8 h-8 font-bold text-[10px] border border-primary/10">
                                    {{ strtoupper(substr($request->employee->first_name, 0, 1) . substr($request->employee->last_name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <div class="font-bold text-sm">{{ $request->employee->full_name }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-sm font-bold">{{ $request->leaveType->name }}</div>
                    </td>
                    <td>
                        <div class="text-sm font-medium">{{ $request->start_date->format('M d, Y') }}</div>
                    </td>
                    <td>
                        <div class="text-sm font-medium">{{ $request->end_date->format('M d, Y') }}</div>
                    </td>
                    <td>
                        <div class="text-sm font-bold text-primary">{{ $request->total_days }} Days</div>
                    </td>
                    <td>
                        @php
                            $statusClasses = [
                                'pending' => 'badge-warning',
                                'approved' => 'badge-success',
                                'rejected' => 'badge-error',
                                'cancelled' => 'badge-ghost',
                            ];
                            $statusBadge = $statusClasses[$request->status] ?? 'badge-ghost';
                        @endphp
                        <span class="badge {{ $statusBadge }} badge-sm font-bold text-[10px] uppercase">{{ $request->status }}</span>
                    </td>
                    <td class="text-right px-6">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('leave.requests.show', $request->id) }}" class="btn btn-ghost btn-xs btn-square text-primary hover:bg-primary/10" title="View Details">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                            @if($request->status === 'pending')
                                <form action="{{ route('leave.requests.status', $request->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-ghost btn-xs btn-square text-success hover:bg-success/10" title="Approve">
                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                    </button>
                                </form>
                                <button onclick="document.getElementById('reject_modal_{{ $request->id }}').showModal()" class="btn btn-ghost btn-xs btn-square text-error hover:bg-error/10" title="Reject">
                                    <span class="material-symbols-outlined text-sm">cancel</span>
                                </button>

                                <dialog id="reject_modal_{{ $request->id }}" class="modal">
                                    <div class="modal-box text-left max-w-sm">
                                        <h3 class="font-bold text-lg mb-4">Reject Request</h3>
                                        <form action="{{ route('leave.requests.status', $request->id) }}" method="POST" class="space-y-4">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <div class="form-control">
                                                <label class="label font-bold" for="rejection_reason">Reason for rejection</label>
                                                <textarea name="rejection_reason" class="textarea textarea-bordered w-full" rows="3" required placeholder="Enter reason..."></textarea>
                                            </div>
                                            <div class="modal-action">
                                                <button type="submit" class="btn btn-error text-error-content whitespace-nowrap">Reject Request</button>
                                                <form method="dialog"><button class="btn btn-ghost">Cancel</button></form>
                                            </div>
                                        </form>
                                    </div>
                                    <form method="dialog" class="modal-backdrop">
                                        <button>close</button>
                                    </form>
                                </dialog>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-20 text-center border-2 border-dashed border-base-300 rounded-xl">
                        <div class="flex flex-col items-center gap-4 opacity-40">
                            <span class="material-symbols-outlined text-6xl">leak_remove</span>
                            <p class="font-bold text-sm">No leave requests found.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if($requests->hasPages())
            <div class="p-6 bg-base-200/30 border-t border-base-200">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
