<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('leave.requests.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-3 shadow-sm flex items-center justify-center">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Leave Request</h2>
                        @php
                            $statusConfig = [
                                'pending' => ['class' => 'bg-amber-50 text-amber-700 border-amber-200/60', 'icon' => 'schedule'],
                                'approved' => ['class' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60', 'icon' => 'check_circle'],
                                'rejected' => ['class' => 'bg-rose-50 text-rose-700 border-rose-200/60', 'icon' => 'cancel'],
                                'cancelled' => ['class' => 'bg-slate-50 text-slate-400 border-slate-200/50', 'icon' => 'block'],
                            ];
                            $config = $statusConfig[$leaveRequest->status] ?? $statusConfig['pending'];
                        @endphp
                        <span class="px-2 py-0.5 rounded-lg border text-[10px] font-bold uppercase tracking-wide flex items-center gap-1.5 {{ $config['class'] }}">
                            <span class="material-symbols-outlined text-xs">{{ $config['icon'] }}</span>
                            {{ $leaveRequest->status }}
                        </span>
                    </div>
                    <p class="text-xs font-medium mt-0.5 text-slate-500">
                        {{ $leaveRequest->leaveType->name }} &mdash; Submitted on {{ $leaveRequest->created_at->format('M d, Y') }}
                    </p>
                </div>
            </div>

            @if($leaveRequest->status === 'pending' && Auth::user()->can('manage_leave'))
            <div class="flex items-center gap-2">
                <form action="{{ route('leave.requests.status', $leaveRequest->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn btn-sm btn-primary rounded-xl px-5 shadow-sm shadow-primary/20 text-white font-semibold text-xs">
                        Approve Request
                    </button>
                </form>
                <button onclick="document.getElementById('reject_modal').showModal()" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-rose-600 rounded-xl px-4 shadow-sm text-xs font-semibold">
                    Reject
                </button>
            </div>
            @endif
        </div>
    </x-slot>

    {{-- Rejection Modal --}}
    <dialog id="reject_modal" class="modal">
        <div class="modal-box bg-white border border-slate-200 rounded-xl shadow-2xl p-6 md:p-8 max-w-sm text-left">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-5 border border-rose-100">
                <span class="material-symbols-outlined text-2xl">cancel</span>
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-1.5">Reject Request</h3>
            <p class="text-xs text-slate-500 mb-5 leading-relaxed">Please provide a reason for rejecting this leave request. This will be visible to the employee.</p>
            
            <form action="{{ route('leave.requests.status', $leaveRequest->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Rejection Reason</label>
                    <textarea name="rejection_reason" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-xs text-gray-900 bg-white placeholder-slate-400 transition-all shadow-sm min-h-[100px]" required placeholder="Explain why this request is being rejected..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-4 border-t border-slate-100">
                    <button type="submit" class="btn btn-error btn-sm rounded-xl px-5 shadow-sm text-white font-semibold text-xs">Reject</button>
                    <button type="button" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold" onclick="document.getElementById('reject_modal').close()">Cancel</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/30 backdrop-blur-sm"><button>close</button></form>
    </dialog>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Leave Info Card --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">event_available</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-xs text-slate-700 uppercase tracking-wider">Period & Duration</h3>
                            <p class="text-[10px] font-semibold text-slate-400 mt-0.5">Details of the requested time off</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 md:p-8 grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <p class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Start Date</p>
                        <p class="text-xs font-bold text-slate-700">{{ $leaveRequest->start_date->format('D, M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">End Date</p>
                        <p class="text-xs font-bold text-slate-700">{{ $leaveRequest->end_date->format('D, M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Duration</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-xl font-bold text-primary-600">{{ $leaveRequest->total_days }}</span>
                            <span class="text-[10px] font-bold uppercase text-slate-400">{{ $leaveRequest->total_days > 1 ? 'Days' : 'Day' }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Configuration</p>
                        <p class="text-xs font-bold text-slate-700">
                            {{ $leaveRequest->is_half_day ? 'Half Day (' . ucfirst(str_replace('_', ' ', $leaveRequest->half_day_type)) . ')' : 'Full Day' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Reason Card --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 md:p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="material-symbols-outlined text-slate-400 text-lg">description</span>
                    <h3 class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Employee Reason</h3>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/50">
                    <p class="text-xs text-slate-600 leading-relaxed font-semibold italic">
                        "{{ $leaveRequest->reason }}"
                    </p>
                </div>
            </div>

            @if($leaveRequest->rejection_reason)
            <div class="bg-rose-50/50 rounded-xl border border-rose-200 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="material-symbols-outlined text-rose-600 text-lg">error</span>
                    <h3 class="block text-xs font-bold text-rose-700 uppercase tracking-wider">Administrator Feedback</h3>
                </div>
                <div class="bg-white rounded-xl p-4 border border-rose-200/50">
                    <p class="text-xs text-rose-900 leading-relaxed font-bold">
                        {{ $leaveRequest->rejection_reason }}
                    </p>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Employee Quick Info --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h3 class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-4">Employee Profile</h3>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-base shadow-sm border border-slate-200/50">
                        {{ strtoupper(substr($leaveRequest->employee->first_name, 0, 1) . substr($leaveRequest->employee->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-slate-800">{{ $leaveRequest->employee->first_name }} {{ $leaveRequest->employee->last_name }}</h4>
                        <p class="text-[9px] font-bold text-primary-600 tracking-tight mt-0.5">{{ $leaveRequest->employee->employee_id }}</p>
                        <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">{{ $leaveRequest->employee->designation->name ?? 'Staff' }}</p>
                    </div>
                </div>
                <a href="{{ route('hr.employees.show', $leaveRequest->employee->id) }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl w-full text-xs font-semibold">
                    View Full Profile
                </a>
            </div>

            {{-- Audit Info --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h3 class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-4">Timeline & Audit</h3>
                <div class="space-y-4 relative before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                    <div class="flex gap-4 relative">
                        <div class="w-6 h-6 rounded-full bg-emerald-500 border-4 border-white z-10 flex items-center justify-center"></div>
                        <div>
                            <p class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider">Request Submitted</p>
                            <p class="text-xs font-bold text-slate-700 mt-0.5">{{ $leaveRequest->created_at->format('M d, Y @ H:i') }}</p>
                        </div>
                    </div>
                    @if($leaveRequest->status !== 'pending')
                    <div class="flex gap-4 relative">
                        <div class="w-6 h-6 rounded-full {{ $leaveRequest->status === 'approved' ? 'bg-emerald-500' : 'bg-rose-500' }} border-4 border-white z-10"></div>
                        <div>
                            <p class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider">Final Decision</p>
                            <p class="text-xs font-bold text-slate-700 mt-0.5">{{ $leaveRequest->updated_at->format('M d, Y @ H:i') }}</p>
                            @if($leaveRequest->approved_by)
                                <p class="text-[9px] text-slate-400 mt-0.5 font-semibold">By Admin ID: #{{ $leaveRequest->approved_by }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
