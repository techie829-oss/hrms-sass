@php
use App\Core\Constants\PermissionConstants;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Comp-Off Claims</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Earned time-off for working extra days or holidays.</p>
            </div>
            <div class="flex items-center gap-2">
                @can(PermissionConstants::MANAGE_COMP_OFF)
                <button onclick="one_click_settle_modal.showModal()" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-3.5 shadow-sm flex items-center justify-center gap-1.5 font-semibold text-xs">
                    <span class="material-symbols-outlined text-sm text-indigo-600">auto_fix_high</span> One-Click Settle
                </button>
                <button onclick="bulk_grant_modal.showModal()" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-3.5 shadow-sm flex items-center justify-center gap-1.5 font-semibold text-xs">
                    <span class="material-symbols-outlined text-sm text-primary">group_add</span> Bulk Grant
                </button>
                @endcan
                <button onclick="request_compoff_modal.showModal()" class="btn btn-primary btn-sm rounded-xl px-4 shadow-sm shadow-primary/20 text-white font-semibold text-xs flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">add_circle</span> Request Claim
                </button>
            </div>
        </div>
    </x-slot>

    {{-- Module Navigation Tabs --}}
    <div class="flex items-center gap-1 mb-6 bg-slate-100 p-1 rounded-xl border border-slate-200 w-fit">
        <a href="{{ route('leave.requests.index') }}" class="px-4 py-1.5 rounded-lg text-xs font-semibold tracking-tight transition-all {{ request()->routeIs('leave.requests.*') ? 'bg-white shadow-sm text-primary border border-slate-200/60' : 'text-slate-500 hover:text-slate-800' }}">
            Leave Requests
        </a>
        <a href="{{ route('leave.holidays.index') }}" class="px-4 py-1.5 rounded-lg text-xs font-semibold tracking-tight transition-all {{ request()->routeIs('leave.holidays.*') ? 'bg-white shadow-sm text-primary' : 'text-slate-500 hover:text-slate-800' }}">
            Holiday Calendar
        </a>
        <a href="{{ route('leave.comp-off.index') }}" class="px-4 py-1.5 rounded-lg text-xs font-semibold tracking-tight transition-all {{ request()->routeIs('leave.comp-off.*') ? 'bg-white shadow-sm text-primary' : 'text-slate-500 hover:text-slate-800' }}">
            Comp-Off Claims
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="table-crm">
            <x-table :headers="['Employee', 'Worked On', 'Duration', 'Reason', 'Status', 'Settlement', 'Actions']" :striped="false">
                @forelse($requests as $request)
                    <tr class="hover:bg-slate-50/50 transition-all border-b border-slate-100 last:border-0 group">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="bg-slate-100 text-slate-700 rounded-xl w-9 h-9 font-bold text-xs flex items-center justify-center border border-slate-200/60 shadow-sm group-hover:scale-105 transition-transform overflow-hidden">
                                        {{ strtoupper(substr($request->employee->first_name, 0, 1) . substr($request->employee->last_name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold text-xs text-slate-700">{{ $request->employee->full_name }}</div>
                                    <div class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">{{ $request->employee->employee_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-xs text-slate-600 font-medium">
                            {{ $request->worked_on_date->format('M d, Y') }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/40">
                                {{ $request->duration }} Day
                            </span>
                        </td>
                        <td class="py-3 px-4 text-xs text-slate-500 max-w-xs truncate">
                            {{ $request->reason }}
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $statusConfig = [
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                    'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                    'rejected' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                ];
                                $statusClass = $statusConfig[$request->status] ?? 'bg-slate-50 text-slate-400 border-slate-200/50';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg border text-[9px] font-bold uppercase tracking-widest {{ $statusClass }}">
                                {{ $request->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if($request->is_used)
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-emerald-600">Settled</span>
                                    <a href="{{ route('leave.requests.show', $request->leave_request_id) }}" class="text-[9px] font-semibold text-slate-400 hover:text-primary transition-colors">
                                        Used on {{ $request->used_at->format('M d, Y') }}
                                    </a>
                                </div>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-50 text-slate-400 border border-slate-200/30">Unused</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            @if($request->status === 'pending' && Auth::user()->can(PermissionConstants::MANAGE_COMP_OFF))
                            <div class="flex justify-end items-center gap-2">
                                <form action="{{ route('leave.comp-off.status', $request->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-ghost btn-xs text-emerald-600 hover:bg-emerald-50 font-bold px-2 py-1 rounded-lg">Approve</button>
                                </form>
                                <form action="{{ route('leave.comp-off.status', $request->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-ghost btn-xs text-rose-600 hover:bg-rose-50 font-bold px-2 py-1 rounded-lg">Reject</button>
                                </form>
                            </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-2 opacity-45">
                                <span class="material-symbols-outlined text-4xl">leak_remove</span>
                                <p class="font-bold text-sm text-slate-600">No compensatory off claims found.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </div>
        @if($requests->hasPages())
            <div class="p-4 bg-slate-50 border-t border-slate-100">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    {{-- Bulk Grant Modal --}}
    <dialog id="bulk_grant_modal" class="modal">
        <div class="modal-box bg-white border border-slate-200 rounded-xl shadow-2xl p-6 md:p-8 max-w-sm text-left">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-900">Bulk Grant Comp-Off</h3>
                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
            </div>
            <p class="text-xs font-semibold text-slate-400 mb-5 leading-relaxed">This will automatically grant 1.0 Comp-Off day to all employees present on the selected date.</p>
            
            <form action="{{ route('leave.comp-off.bulk-grant') }}" method="POST" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reference Date</label>
                    <input type="date" name="date" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                </div>
                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reason (Internal Note)</label>
                    <input type="text" name="reason" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required placeholder="e.g. Worked on Sunday for Project X">
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold" onclick="document.getElementById('bulk_grant_modal').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20 text-white font-semibold text-xs">Grant to All</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/30 backdrop-blur-sm"><button>close</button></form>
    </dialog>

    {{-- Request Modal --}}
    <dialog id="request_compoff_modal" class="modal">
        <div class="modal-box bg-white border border-slate-200 rounded-xl shadow-2xl p-6 md:p-8 max-w-sm text-left">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-900">Claim Comp-Off</h3>
                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
            </div>
            
            <form action="{{ route('leave.comp-off.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Worked On Date</label>
                    <input type="date" name="worked_on_date" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                </div>
                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Duration</label>
                    <select name="duration" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                        <option value="1.0">Full Day (1.0)</option>
                        <option value="0.5">Half Day (0.5)</option>
                    </select>
                </div>
                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reason / Task Accomplished</label>
                    <textarea name="reason" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-xs text-gray-900 bg-white placeholder-slate-400 transition-all shadow-sm min-h-[100px]" required placeholder="Describe the extra work done..."></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold" onclick="document.getElementById('request_compoff_modal').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20 text-white font-semibold text-xs">Submit Claim</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/30 backdrop-blur-sm"><button>close</button></form>
    </dialog>

    {{-- One-Click Settle Modal --}}
    <dialog id="one_click_settle_modal" class="modal">
        <div class="modal-box bg-white border border-slate-200 rounded-xl shadow-2xl p-6 md:p-8 max-w-sm text-left">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-900">One-Click Settlement</h3>
                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
            </div>
            <p class="text-xs font-semibold text-slate-400 mb-5 leading-relaxed">Match an earned date to a usage date. Employees present on the usage date will be skipped.</p>
            
            <form action="{{ route('leave.comp-off.one-click-settle') }}" method="POST" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Date Worked (Reference)</label>
                    <input type="date" name="reference_date" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                </div>
                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Date to Use (Target)</label>
                    <input type="date" name="target_date" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold" onclick="document.getElementById('one_click_settle_modal').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20 text-white font-semibold text-xs">Process Settle</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/30 backdrop-blur-sm"><button>close</button></form>
    </dialog>
</x-app-layout>
