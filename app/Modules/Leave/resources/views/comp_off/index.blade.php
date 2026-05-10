<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-base-content/90 tracking-tight">Comp-Off Claims</h2>
                <p class="text-xs font-medium mt-0.5 opacity-50">Earned time-off for working extra days or holidays.</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="request_compoff_modal.showModal()" class="btn btn-primary btn-sm rounded-xl px-5 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-base">add_circle</span> Request Claim
                </button>
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

    <div class="bg-base-100 rounded-[32px] shadow-sm border border-base-200 overflow-hidden">
        <x-table :headers="['Employee', 'Worked On', 'Duration', 'Reason', 'Status', 'Actions']" :striped="false">
            @forelse($requests as $request)
                <tr class="hover:bg-base-200/50 transition-colors border-b border-base-200">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-black text-[10px]">
                                {{ strtoupper(substr($request->employee->first_name, 0, 1)) }}
                            </div>
                            <div class="font-bold text-sm">{{ $request->employee->full_name }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="text-sm font-medium">{{ $request->worked_on_date->format('M d, Y') }}</div>
                    </td>
                    <td>
                        <span class="px-2 py-1 bg-base-200 rounded-lg text-xs font-black uppercase">{{ $request->duration }} Day</span>
                    </td>
                    <td>
                        <div class="text-xs text-base-content/60 max-w-xs truncate">{{ $request->reason }}</div>
                    </td>
                    <td>
                        @php
                            $statusConfig = [
                                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                            ];
                            $statusClass = $statusConfig[$request->status] ?? 'bg-slate-100';
                        @endphp
                        <span class="px-2.5 py-1 rounded-lg border text-[9px] font-black uppercase tracking-widest {{ $statusClass }}">
                            {{ $request->status }}
                        </span>
                    </td>
                    <td class="text-right px-6">
                        @if($request->status === 'pending' && Auth::user()->can('manage comp_off'))
                        <div class="flex justify-end gap-2">
                            <form action="{{ route('leave.comp-off.status', $request->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-ghost btn-xs text-emerald-600 hover:bg-emerald-50 font-bold">Approve</button>
                            </form>
                            <form action="{{ route('leave.comp-off.status', $request->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-ghost btn-xs text-rose-600 hover:bg-rose-50 font-bold">Reject</button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-12 text-center opacity-40">No compensatory off claims found.</td>
                </tr>
            @endforelse
        </x-table>
        <div class="p-6">
            {{ $requests->links() }}
        </div>
    </div>

    {{-- Request Modal --}}
    <dialog id="request_compoff_modal" class="modal">
        <div class="modal-box bg-base-100 rounded-[32px] p-8 shadow-2xl border border-base-200 max-w-sm">
            <h3 class="text-xl font-bold text-base-content/90 mb-6">Claim Comp-Off</h3>
            
            <form action="{{ route('leave.comp-off.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Worked On Date</label>
                    <input type="date" name="worked_on_date" class="input input-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100" required>
                </div>
                <div class="form-control">
                    <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Duration</label>
                    <select name="duration" class="select select-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100" required>
                        <option value="1.0">Full Day (1.0)</option>
                        <option value="0.5">Half Day (0.5)</option>
                    </select>
                </div>
                <div class="form-control">
                    <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Reason / Task Accomplished</label>
                    <textarea name="reason" class="textarea textarea-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100 min-h-[100px]" required placeholder="Describe the extra work done..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-4">
                    <button type="submit" class="btn bg-primary hover:bg-primary-focus text-white border-none rounded-2xl font-bold">Submit Claim</button>
                    <form method="dialog"><button class="btn btn-ghost rounded-2xl font-bold w-full">Cancel</button></form>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-base-300/40 backdrop-blur-sm"><button>close</button></form>
    </dialog>
</x-app-layout>
