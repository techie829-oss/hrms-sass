@php
use App\Core\Constants\PermissionConstants;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Leave Management</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Track and manage employee time-off requests.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                @can(PermissionConstants::MANAGE_SETTINGS)
                <button onclick="bulk_apply_modal.showModal()" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-3.5 shadow-sm flex items-center justify-center gap-1.5 font-semibold text-xs flex-1 sm:flex-none">
                    <span class="material-symbols-outlined text-sm">group_add</span> Bulk Apply
                </button>
                <a href="{{ route('leave.types.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-3.5 shadow-sm flex items-center justify-center gap-1.5 font-semibold text-xs flex-1 sm:flex-none">
                    <span class="material-symbols-outlined text-sm">category</span> Leave Types
                </a>
                @endcan
                @can('create', App\Modules\Leave\Models\LeaveRequest::class)
                <a href="{{ route('leave.requests.create') }}" class="btn btn-primary btn-sm rounded-xl px-4 shadow-sm shadow-primary/20 text-white font-semibold text-xs flex items-center justify-center gap-1 flex-1 sm:flex-none">
                    <span class="material-symbols-outlined text-sm">add</span> Apply Leave
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    {{-- Module Navigation Tabs --}}
    <div class="flex items-center gap-1 mb-6 bg-slate-100 p-1 rounded-xl border border-slate-200 w-full sm:w-fit overflow-x-auto">
        <a href="{{ route('leave.requests.index') }}" class="px-4 py-1.5 rounded-lg text-xs font-semibold tracking-tight transition-all whitespace-nowrap shrink-0 {{ request()->routeIs('leave.requests.*') ? 'bg-white shadow-sm text-primary border border-slate-200/60' : 'text-slate-500 hover:text-slate-800' }}">
            Leave Requests
        </a>
        <a href="{{ route('leave.holidays.index') }}" class="px-4 py-1.5 rounded-lg text-xs font-semibold tracking-tight transition-all whitespace-nowrap shrink-0 {{ request()->routeIs('leave.holidays.*') ? 'bg-white shadow-sm text-primary' : 'text-slate-500 hover:text-slate-800' }}">
            Holiday Calendar
        </a>
        <a href="{{ route('leave.comp-off.index') }}" class="px-4 py-1.5 rounded-lg text-xs font-semibold tracking-tight transition-all whitespace-nowrap shrink-0 {{ request()->routeIs('leave.comp-off.*') ? 'bg-white shadow-sm text-primary' : 'text-slate-500 hover:text-slate-800' }}">
            Comp-Off Claims
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        {{-- Desktop Table View --}}
        <div class="hidden lg:block table-crm">
            <x-table :headers="['Employee', 'Leave Type', 'Start Date', 'End Date', 'Days', 'Status', 'Actions']" :striped="false">
                @forelse($requests as $request)
                    @php
                        $colors = ['bg-blue-600', 'bg-indigo-600', 'bg-emerald-600', 'bg-slate-700', 'bg-teal-600'];
                        $colorClass = $colors[$request->employee->id % count($colors)];
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-all border-b border-slate-100 last:border-0 group">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl {{ !empty($request->employee->profile_photo) ? 'bg-slate-100 text-slate-800' : $colorClass . ' text-white' }} font-bold text-xs flex items-center justify-center shrink-0 border border-slate-200/60 shadow-sm group-hover:scale-105 transition-transform overflow-hidden">
                                    @if(!empty($request->employee->profile_photo))
                                        <img src="{{ asset('storage/' . $request->employee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        @php
                                            $nameParts = explode(' ', trim($request->employee->full_name ?? 'U'));
                                            $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                                        @endphp
                                        {{ $initials }}
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-xs text-slate-700">{{ $request->employee->full_name }}</div>
                                    <div class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">{{ $request->employee->employee_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-xs font-semibold text-slate-700">
                            {{ $request->leaveType->name }}
                        </td>
                        <td class="py-3 px-4 text-xs text-slate-600 font-medium">
                            {{ $request->start_date->format('M d, Y') }}
                        </td>
                        <td class="py-3 px-4 text-xs text-slate-600 font-medium">
                            {{ $request->end_date->format('M d, Y') }}
                        </td>
                        <td class="py-3 px-4 text-xs font-bold text-primary-600">
                            {{ $request->total_days }} {{ $request->total_days > 1 ? 'Days' : 'Day' }}
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                    'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                    'rejected' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                    'cancelled' => 'bg-slate-50 text-slate-400 border-slate-200/50',
                                ];
                                $statusBadge = $statusClasses[$request->status] ?? 'bg-slate-50 text-slate-400 border-slate-200/50';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold border {{ $statusBadge }} uppercase">{{ $request->status }}</span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                @can('view', $request)
                                <a href="{{ route('leave.requests.show', $request->id) }}" class="btn btn-ghost btn-xs btn-square text-slate-400 hover:text-primary transition-colors" title="View Details">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                </a>
                                @endcan
                                
                                @if($request->status === 'pending')
                                    @can('approve', $request)
                                    <form action="{{ route('leave.requests.status', $request->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-ghost btn-xs btn-square text-slate-400 hover:text-success transition-colors" title="Approve">
                                            <span class="material-symbols-outlined text-sm">check_circle</span>
                                        </button>
                                    </form>
                                    <button onclick="document.getElementById('reject_modal_{{ $request->id }}').showModal()" class="btn btn-ghost btn-xs btn-square text-slate-400 hover:text-error transition-colors" title="Reject">
                                        <span class="material-symbols-outlined text-sm">cancel</span>
                                    </button>

                                    <dialog id="reject_modal_{{ $request->id }}" class="modal">
                                        <div class="modal-box text-left max-w-sm bg-white border border-slate-200 rounded-xl shadow-2xl p-6">
                                            <h3 class="font-bold text-sm text-slate-800 mb-4">Reject Request</h3>
                                            <form action="{{ route('leave.requests.status', $request->id) }}" method="POST" class="space-y-4">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <div class="form-control">
                                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="rejection_reason">Reason for rejection</label>
                                                    <textarea name="rejection_reason" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-xs text-gray-900 bg-white placeholder-slate-400 transition-all shadow-sm" rows="3" required placeholder="Enter reason..."></textarea>
                                                </div>
                                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                                    <button type="button" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold" onclick="document.getElementById('reject_modal_{{ $request->id }}').close()">Cancel</button>
                                                    <button type="submit" class="btn btn-error btn-sm rounded-xl px-5 shadow-sm text-white font-semibold text-xs">Reject Request</button>
                                                </div>
                                            </form>
                                        </div>
                                        <form method="dialog" class="modal-backdrop"><button>close</button></form>
                                    </dialog>
                                    @endcan
                                    
                                    @can('cancel', $request)
                                    <form action="{{ route('leave.requests.status', $request->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="btn btn-ghost btn-xs btn-square text-slate-400 hover:text-slate-600 transition-colors" title="Cancel Request">
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
                        <td colspan="7" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-2 opacity-45">
                                <span class="material-symbols-outlined text-4xl">leak_remove</span>
                                <p class="font-bold text-sm text-slate-600">No leave requests found.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </div>

        {{-- Mobile Card View --}}
        <div class="lg:hidden divide-y divide-slate-100">
            @forelse($requests as $request)
                @php
                    $colors = ['bg-blue-600', 'bg-indigo-600', 'bg-emerald-600', 'bg-slate-700', 'bg-teal-600'];
                    $colorClass = $colors[$request->employee->id % count($colors)];
                    $statusClasses = [
                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                        'cancelled' => 'bg-slate-50 text-slate-400 border-slate-200/50',
                    ];
                    $statusBadge = $statusClasses[$request->status] ?? 'bg-slate-50 text-slate-400 border-slate-200/50';
                @endphp
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-9 h-9 rounded-xl {{ !empty($request->employee->profile_photo) ? 'bg-slate-100 text-slate-800' : $colorClass . ' text-white' }} font-bold text-xs flex items-center justify-center shrink-0 border border-slate-200/60 shadow-sm overflow-hidden">
                                @if(!empty($request->employee->profile_photo))
                                    <img src="{{ asset('storage/' . $request->employee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    @php
                                        $nameParts = explode(' ', trim($request->employee->full_name ?? 'U'));
                                        $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                                    @endphp
                                    {{ $initials }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-xs text-slate-800 truncate">{{ $request->employee->full_name }}</div>
                                <div class="text-[10px] font-medium text-slate-500 truncate">{{ $request->leaveType->name }}</div>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold border {{ $statusBadge }} uppercase shrink-0">{{ $request->status }}</span>
                    </div>

                    <div class="bg-slate-50/80 rounded-xl p-2.5 flex items-center justify-between text-xs border border-slate-100">
                        <div class="flex items-center gap-1.5 text-slate-600">
                            <span class="material-symbols-outlined text-sm text-slate-400">calendar_today</span>
                            <span class="font-medium">{{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d, Y') }}</span>
                        </div>
                        <span class="font-bold text-primary-600">{{ $request->total_days }} {{ $request->total_days > 1 ? 'Days' : 'Day' }}</span>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-1">
                        @can('view', $request)
                        <a href="{{ route('leave.requests.show', $request->id) }}" class="btn btn-ghost btn-xs border border-slate-200/60 bg-white text-slate-600 hover:text-primary rounded-lg">
                            <span class="material-symbols-outlined text-sm">visibility</span> View
                        </a>
                        @endcan

                        @if($request->status === 'pending')
                            @can('approve', $request)
                            <form action="{{ route('leave.requests.status', $request->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-xs bg-emerald-50 text-emerald-700 border border-emerald-200/60 hover:bg-emerald-100 rounded-lg">
                                    <span class="material-symbols-outlined text-sm">check_circle</span> Approve
                                </button>
                            </form>
                            <button onclick="document.getElementById('reject_modal_mob_{{ $request->id }}').showModal()" class="btn btn-xs bg-rose-50 text-rose-700 border border-rose-200/60 hover:bg-rose-100 rounded-lg">
                                <span class="material-symbols-outlined text-sm">cancel</span> Reject
                            </button>

                            <dialog id="reject_modal_mob_{{ $request->id }}" class="modal">
                                <div class="modal-box text-left max-w-sm bg-white border border-slate-200 rounded-xl shadow-2xl p-6">
                                    <h3 class="font-bold text-sm text-slate-800 mb-4">Reject Request</h3>
                                    <form action="{{ route('leave.requests.status', $request->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <div class="form-control">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="rejection_reason">Reason for rejection</label>
                                            <textarea name="rejection_reason" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-xs text-gray-900 bg-white placeholder-slate-400 transition-all shadow-sm" rows="3" required placeholder="Enter reason..."></textarea>
                                        </div>
                                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                            <button type="button" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold" onclick="document.getElementById('reject_modal_mob_{{ $request->id }}').close()">Cancel</button>
                                            <button type="submit" class="btn btn-error btn-sm rounded-xl px-5 shadow-sm text-white font-semibold text-xs">Reject Request</button>
                                        </div>
                                    </form>
                                </div>
                                <form method="dialog" class="modal-backdrop"><button>close</button></form>
                            </dialog>
                            @endcan

                            @can('cancel', $request)
                            <form action="{{ route('leave.requests.status', $request->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn btn-ghost btn-xs border border-slate-200/60 bg-white text-slate-500 hover:text-slate-700 rounded-lg">
                                    Cancel
                                </button>
                            </form>
                            @endcan
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-16 text-center">
                    <div class="flex flex-col items-center gap-2 opacity-45">
                        <span class="material-symbols-outlined text-4xl">leak_remove</span>
                        <p class="font-bold text-sm text-slate-600">No leave requests found.</p>
                    </div>
                </div>
            @endforelse
        </div>
        @if($requests->hasPages())
            <div class="p-4 bg-slate-50 border-t border-slate-100">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    {{-- Bulk Apply Modal --}}
    <dialog id="bulk_apply_modal" class="modal">
        <div class="modal-box bg-white border border-slate-200 rounded-xl shadow-2xl p-6 md:p-8 max-w-2xl text-left">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-900">Bulk Apply Leave</h3>
                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
            </div>
            
            <form action="{{ route('leave.requests.bulk') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="form-control">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Select Employees</label>
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-200 max-h-48 overflow-y-auto space-y-2">
                            @foreach($employees as $emp)
                                <label class="flex items-center gap-3 cursor-pointer hover:bg-white p-1.5 rounded-lg transition-colors border border-transparent hover:border-slate-100">
                                    <input type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" class="checkbox checkbox-primary checkbox-sm rounded-md">
                                    <span class="text-xs font-semibold text-slate-700">{{ $emp->full_name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="form-control">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Leave Type</label>
                            <select name="leave_type_id" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Start Date</label>
                            <input type="date" name="start_date" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                        </div>
                        <div class="form-control">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">End Date</label>
                            <input type="date" name="end_date" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                        </div>
                    </div>
                </div>

                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reason for Bulk Application</label>
                    <textarea name="reason" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-xs text-gray-900 bg-white placeholder-slate-400 transition-all shadow-sm min-h-[80px]" required placeholder="e.g. Finance Month Closing Recovery Day Off"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold" onclick="document.getElementById('bulk_apply_modal').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20 text-white font-semibold text-xs">Apply for All Selected</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/30 backdrop-blur-sm"><button>close</button></form>
    </dialog>
</x-app-layout>
