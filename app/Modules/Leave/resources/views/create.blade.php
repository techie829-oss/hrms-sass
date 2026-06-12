<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('leave.requests.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-3 shadow-sm flex items-center justify-center">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Apply for Leave</h2>
                    <p class="text-xs font-medium mt-0.5 text-slate-500">Submit your request for time away from the office.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Column --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6 md:p-8">
                    <form action="{{ route('leave.requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @if($isAdmin)
                            <div class="form-control w-full">
                                <label for="employee_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Employee Select <span class="text-rose-500">*</span></label>
                                <select id="employee_id" name="employee_id" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                                    <option value="" disabled selected>Select an employee...</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ count($employees) === 1 ? 'selected' : '' }}>
                                            {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                                @php $employee = $employees->first(); @endphp
                                <div class="form-control w-full">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Applying For</label>
                                    @if($employee)
                                    <div class="px-4 py-2 bg-slate-50 rounded-xl border border-slate-200 text-xs font-bold flex items-center gap-3 text-slate-700">
                                        <div class="w-7 h-7 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xs">
                                            {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                                        </div>
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                    </div>
                                    @else
                                    <div class="px-4 py-3 bg-rose-50 rounded-xl border border-rose-200 text-xs font-bold flex items-center gap-3 text-rose-700">
                                        No employee profile linked to your account. Please contact HR.
                                    </div>
                                    @endif
                                </div>
                            @endif

                            <div class="form-control w-full">
                                <label for="leave_type_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Leave Type <span class="text-rose-500">*</span></label>
                                <select id="leave_type_id" name="leave_type_id" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                                    <option value="" disabled selected>Choose type...</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-control w-full">
                                <label for="start_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Start Date <span class="text-rose-500">*</span></label>
                                <input id="start_date" name="start_date" type="date" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required />
                            </div>

                            <div class="form-control w-full">
                                <label for="end_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">End Date <span class="text-rose-500">*</span></label>
                                <input id="end_date" name="end_date" type="date" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required />
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="is_half_day" name="is_half_day" value="1" class="checkbox checkbox-primary rounded-lg checkbox-sm" onchange="toggleHalfDay(this)">
                                <div>
                                    <label for="is_half_day" class="text-xs font-bold cursor-pointer text-slate-700">Partial Day Request</label>
                                    <p class="text-[10px] font-semibold text-slate-400 mt-0.5">Select if you are applying for only half a day.</p>
                                </div>
                            </div>
                            
                            <div id="half_day_options" class="hidden">
                                <select name="half_day_type" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white font-semibold shadow-sm transition-all">
                                    <option value="first_half">First Half Session</option>
                                    <option value="second_half">Second Half Session</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-control w-full">
                            <label for="reason" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reason for Absence <span class="text-rose-500">*</span></label>
                            <textarea id="reason" name="reason" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-xs text-gray-900 bg-white placeholder-slate-400 transition-all shadow-sm min-h-[120px]" required placeholder="Briefly describe why you are taking this leave..."></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <a href="{{ route('leave.requests.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20 text-white font-semibold text-xs">
                                Submit Leave Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Info Column --}}
        <div class="space-y-6">
            {{-- Balance Overview --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h3 class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-4">Current Balance ({{ now()->year }})</h3>
                <div class="space-y-3">
                    @forelse($balances as $bal)
                        <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/50">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-700">{{ $bal->leaveType->name }}</span>
                                <span class="text-xs font-bold text-primary-600">{{ (float)$bal->balance }} / {{ (float)$bal->total_allocated }}</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                @php 
                                    $percent = ($bal->total_allocated > 0) ? ($bal->total_used / $bal->total_allocated) * 100 : 0;
                                @endphp
                                <div class="h-full bg-primary rounded-full" style="width: {{ 100 - $percent }}%"></div>
                            </div>
                            <p class="text-[9px] font-bold text-slate-400 mt-2 uppercase tracking-tighter">{{ (float)$bal->total_used }} Days Used so far</p>
                        </div>
                    @empty
                        <div class="text-center py-8 opacity-45">
                            <span class="material-symbols-outlined text-3xl mb-2 text-slate-400">info</span>
                            <p class="text-xs font-semibold text-slate-500">No active quotas found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Support Card --}}
            <div class="bg-primary-900 border border-primary-900 rounded-xl shadow-sm p-6 text-white">
                <h4 class="font-bold text-sm mb-1.5 tracking-tight">Need Assistance?</h4>
                <p class="text-xs font-medium text-primary-100/80 leading-relaxed mb-5">
                    If you have questions about your leave eligibility or the approval process, contact your HR manager.
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm">support_agent</span>
                    </div>
                    <span class="text-[9px] font-bold uppercase tracking-widest text-primary-100">HR Support Center</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleHalfDay(checkbox) {
            const options = document.getElementById('half_day_options');
            if (checkbox.checked) {
                options.classList.remove('hidden');
            } else {
                options.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
