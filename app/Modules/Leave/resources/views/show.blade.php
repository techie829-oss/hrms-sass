<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('leave.requests.index') }}" class="w-10 h-10 rounded-xl bg-base-200 flex items-center justify-center hover:bg-base-300 transition-colors">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-base-content/90 tracking-tight">Leave Request</h2>
                        @php
                            $statusConfig = [
                                'pending' => ['class' => 'bg-amber-100 text-amber-700 border-amber-200', 'icon' => 'schedule'],
                                'approved' => ['class' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'icon' => 'check_circle'],
                                'rejected' => ['class' => 'bg-rose-100 text-rose-700 border-rose-200', 'icon' => 'cancel'],
                                'cancelled' => ['class' => 'bg-slate-100 text-slate-700 border-slate-200', 'icon' => 'block'],
                            ];
                            $config = $statusConfig[$leaveRequest->status] ?? $statusConfig['pending'];
                        @endphp
                        <span class="px-2.5 py-1 rounded-lg border text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5 {{ $config['class'] }}">
                            <span class="material-symbols-outlined text-xs">{{ $config['icon'] }}</span>
                            {{ $leaveRequest->status }}
                        </span>
                    </div>
                    <p class="text-xs font-medium mt-0.5 opacity-50">
                        {{ $leaveRequest->leaveType->name }} &mdash; Submitted on {{ $leaveRequest->created_at->format('M d, Y') }}
                    </p>
                </div>
            </div>

            @if($leaveRequest->status === 'pending' && Auth::user()->can('manage-leave'))
            <div class="flex items-center gap-2">
                <form action="{{ route('leave.requests.status', $leaveRequest->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn btn-sm bg-emerald-500 hover:bg-emerald-600 border-none text-white rounded-xl font-bold px-6">
                        Approve Request
                    </button>
                </form>
                <button onclick="document.getElementById('reject_modal').showModal()" class="btn btn-sm btn-ghost text-rose-600 hover:bg-rose-50 rounded-xl font-bold px-6">
                    Reject
                </button>
            </div>
            @endif
        </div>
    </x-slot>

    {{-- Rejection Modal --}}
    <dialog id="reject_modal" class="modal">
        <div class="modal-box bg-base-100 rounded-[32px] p-8 shadow-2xl border border-base-200 max-w-sm">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-2xl">cancel</span>
            </div>
            <h3 class="text-xl font-bold text-base-content/90 mb-2">Reject Request</h3>
            <p class="text-sm text-base-content/60 mb-6">Please provide a reason for rejecting this leave request. This will be visible to the employee.</p>
            
            <form action="{{ route('leave.requests.status', $leaveRequest->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <div class="form-control">
                    <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Rejection Reason</label>
                    <textarea name="rejection_reason" class="textarea textarea-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100 min-h-[100px]" required placeholder="Explain why this request is being rejected..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-4">
                    <button type="submit" class="btn bg-rose-600 hover:bg-rose-700 text-white border-none rounded-2xl font-bold">Reject</button>
                    <form method="dialog"><button class="btn btn-ghost rounded-2xl font-bold w-full">Cancel</button></form>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-base-300/40 backdrop-blur-sm"><button>close</button></form>
    </dialog>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Leave Info Card --}}
            <div class="bg-base-100 rounded-[32px] shadow-sm border border-base-200 overflow-hidden">
                <div class="p-8 border-b border-base-100 bg-base-200/20">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl">event_available</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-base-content/90 tracking-tight">Period & Duration</h3>
                            <p class="text-xs text-base-content/50">Details of the requested time off</p>
                        </div>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-1.5">Start Date</p>
                        <p class="text-sm font-bold text-base-content/80">{{ $leaveRequest->start_date->format('D, M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-1.5">End Date</p>
                        <p class="text-sm font-bold text-base-content/80">{{ $leaveRequest->end_date->format('D, M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-1.5">Total Duration</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-black text-primary">{{ $leaveRequest->total_days }}</span>
                            <span class="text-[10px] font-bold uppercase text-base-content/40">Days</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-1.5">Configuration</p>
                        <p class="text-sm font-bold text-base-content/80">
                            {{ $leaveRequest->is_half_day ? 'Half Day (' . ucfirst(str_replace('_', ' ', $leaveRequest->half_day_type)) . ')' : 'Full Day' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Reason Card --}}
            <div class="bg-base-100 rounded-[32px] shadow-sm border border-base-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-primary/60">description</span>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-base-content/40">Employee Reason</h3>
                </div>
                <div class="bg-base-200/30 rounded-2xl p-6 border border-base-200/50">
                    <p class="text-sm text-base-content/70 leading-relaxed font-medium">
                        "{{ $leaveRequest->reason }}"
                    </p>
                </div>
            </div>

            @if($leaveRequest->rejection_reason)
            <div class="bg-rose-50 rounded-[32px] border border-rose-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-rose-600">error</span>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-rose-600/60">Administrator Feedback</h3>
                </div>
                <div class="bg-white/80 rounded-2xl p-6 border border-rose-200/50">
                    <p class="text-sm text-rose-900 leading-relaxed font-bold">
                        {{ $leaveRequest->rejection_reason }}
                    </p>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-8">
            {{-- Employee Quick Info --}}
            <div class="bg-base-100 rounded-[32px] shadow-sm border border-base-200 p-8">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-6">Employee Profile</h3>
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-black text-xl shadow-inner">
                        {{ strtoupper(substr($leaveRequest->employee->first_name, 0, 1) . substr($leaveRequest->employee->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-base-content/90 text-lg">{{ $leaveRequest->employee->first_name }} {{ $leaveRequest->employee->last_name }}</h4>
                        <p class="text-xs font-bold text-primary tracking-tight">{{ $leaveRequest->employee->employee_id }}</p>
                        <p class="text-[10px] font-medium opacity-50">{{ $leaveRequest->employee->designation->name ?? 'Staff' }}</p>
                    </div>
                </div>
                <a href="{{ route('hr.employees.show', $leaveRequest->employee->id) }}" class="btn btn-sm btn-ghost w-full rounded-xl font-bold uppercase text-[10px] tracking-widest hover:bg-primary hover:text-white transition-all">
                    View Full Profile
                </a>
            </div>

            {{-- Audit Info --}}
            <div class="bg-base-100 rounded-[32px] shadow-sm border border-base-200 p-8">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-6">Timeline & Audit</h3>
                <div class="space-y-6 relative before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-0.5 before:bg-base-200">
                    <div class="flex gap-4 relative">
                        <div class="w-6 h-6 rounded-full bg-emerald-500 border-4 border-base-100 z-10 flex items-center justify-center"></div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-base-content/40">Request Submitted</p>
                            <p class="text-xs font-bold">{{ $leaveRequest->created_at->format('M d, Y @ H:i') }}</p>
                        </div>
                    </div>
                    @if($leaveRequest->status !== 'pending')
                    <div class="flex gap-4 relative">
                        <div class="w-6 h-6 rounded-full {{ $leaveRequest->status === 'approved' ? 'bg-emerald-500' : 'bg-rose-500' }} border-4 border-base-100 z-10"></div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-base-content/40">Final Decision</p>
                            <p class="text-xs font-bold">{{ $leaveRequest->updated_at->format('M d, Y @ H:i') }}</p>
                            @if($leaveRequest->approved_by)
                                <p class="text-[10px] opacity-50 mt-0.5 font-medium">By Admin ID: #{{ $leaveRequest->approved_by }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
