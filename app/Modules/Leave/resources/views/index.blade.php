<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-base-content/90 tracking-tight">Leave Management</h2>
                <p class="text-xs font-medium mt-0.5 opacity-50">Track and manage employee time-off requests.</p>
            </div>
            <div class="flex items-center gap-2">
                @can('manage-settings')
                <button onclick="bulk_apply_modal.showModal()" class="btn btn-ghost btn-sm border-base-300 rounded-xl px-4 font-bold text-primary">
                    <span class="material-symbols-outlined text-base">group_add</span> Bulk Apply
                </button>
                <a href="{{ route('leave.types.index') }}" class="btn btn-ghost btn-sm btn-outline border-base-300 rounded-xl px-4">
                    <span class="material-symbols-outlined text-base">category</span> Leave Types
                </a>
                @endcan
                @can('create', App\Modules\Leave\Models\LeaveRequest::class)
                <a href="{{ route('leave.requests.create') }}" class="btn btn-primary btn-sm rounded-xl px-5 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-base">add</span> Apply Leave
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    {{-- Module Navigation Tabs --}}
    <div class="flex items-center gap-1 mb-8 bg-base-200/50 p-1.5 rounded-[20px] w-fit border border-base-200">
        <a href="{{ route('leave.requests.index') }}" class="px-6 py-2 rounded-[14px] text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('leave.requests.*') ? 'bg-white shadow-sm text-primary' : 'text-base-content/40 hover:text-base-content/70' }}">
            Leave Requests
        </a>
        <a href="{{ route('leave.holidays.index') }}" class="px-6 py-2 rounded-[14px] text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('leave.holidays.*') ? 'bg-white shadow-sm text-primary' : 'text-base-content/40 hover:text-base-content/70' }}">
            Holiday Calendar
        </a>
        <a href="{{ route('leave.comp-off.index') }}" class="px-6 py-2 rounded-[14px] text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('leave.comp-off.*') ? 'bg-white shadow-sm text-primary' : 'text-base-content/40 hover:text-base-content/70' }}">
            Comp-Off Claims
        </a>
    </div>

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
                            @can('view', $request)
                            <a href="{{ route('leave.requests.show', $request->id) }}" class="btn btn-ghost btn-xs btn-square text-primary hover:bg-primary/10" title="View Details">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                            @endcan
                            
                            @if($request->status === 'pending')
                                @can('approve', $request)
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
                                @endcan
                                
                                @can('cancel', $request)
                                {{-- tstaff can cancel their own pending requests --}}
                                <form action="{{ route('leave.requests.status', $request->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-ghost btn-xs btn-square text-neutral hover:bg-neutral/10" title="Cancel Request">
                                        <span class="material-symbols-outlined text-sm">close</span>
                                    </button>
                                </form>
                                @endcan
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

    {{-- Bulk Apply Modal --}}
    <dialog id="bulk_apply_modal" class="modal">
        <div class="modal-box bg-base-100 rounded-[32px] p-8 shadow-2xl border border-base-200 max-w-2xl">
            <h3 class="text-xl font-bold text-base-content/90 mb-6">Bulk Apply Leave</h3>
            
            <form action="{{ route('leave.requests.bulk') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Select Employees</label>
                        <div class="bg-base-200/50 rounded-2xl p-4 border border-base-200/50 max-h-48 overflow-y-auto space-y-2">
                            @foreach($employees as $emp)
                                <label class="flex items-center gap-3 cursor-pointer hover:bg-white/50 p-2 rounded-xl transition-colors">
                                    <input type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" class="checkbox checkbox-primary checkbox-sm rounded-md">
                                    <span class="text-xs font-bold text-base-content/70">{{ $emp->full_name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="form-control">
                            <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Leave Type</label>
                            <select name="leave_type_id" class="select select-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100" required>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Start Date</label>
                            <input type="date" name="start_date" class="input input-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100" required>
                        </div>
                        <div class="form-control">
                            <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">End Date</label>
                            <input type="date" name="end_date" class="input input-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100" required>
                        </div>
                    </div>
                </div>

                <div class="form-control">
                    <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Reason for Bulk Application</label>
                    <textarea name="reason" class="textarea textarea-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100 min-h-[100px]" required placeholder="e.g. Finance Month Closing Recovery Day Off"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-4">
                    <button type="submit" class="btn bg-primary hover:bg-primary-focus text-white border-none rounded-2xl font-bold">Apply for All Selected</button>
                    <form method="dialog"><button class="btn btn-ghost rounded-2xl font-bold w-full">Cancel</button></form>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-base-300/40 backdrop-blur-sm"><button>close</button></form>
    </dialog>
</x-app-layout>
