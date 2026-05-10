<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('leave.requests.index') }}" class="w-10 h-10 rounded-xl bg-base-200 flex items-center justify-center hover:bg-base-300 transition-colors">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-base-content/90 tracking-tight">Apply for Leave</h2>
                    <p class="text-xs font-medium mt-0.5 opacity-50">Submit your request for time away from the office.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Form Column --}}
        <div class="lg:col-span-2">
            <div class="bg-base-100 rounded-[32px] shadow-sm border border-base-200 p-8 md:p-10">
                <form action="{{ route('leave.requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @if($isAdmin)
                        <div class="form-control w-full">
                            <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Employee Select</label>
                            <select id="employee_id" name="employee_id" class="select select-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100" required>
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
                                <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Applying For</label>
                                <div class="px-5 py-3.5 bg-primary/5 rounded-2xl border border-primary/10 text-sm font-bold flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-primary/20 text-primary flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                                    </div>
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                    <input type="hidden" name="employee_id" value="{{ $employee->id ?? '' }}">
                                </div>
                            </div>
                        @endif

                        <div class="form-control w-full">
                            <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Leave Type</label>
                            <select id="leave_type_id" name="leave_type_id" class="select select-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100" required>
                                <option value="" disabled selected>Choose type...</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control w-full">
                            <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Start Date</label>
                            <input id="start_date" name="start_date" type="date" class="input input-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100" required />
                        </div>

                        <div class="form-control w-full">
                            <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">End Date</label>
                            <input id="end_date" name="end_date" type="date" class="input input-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100" required />
                        </div>
                    </div>

                    <div class="bg-base-200/30 p-6 rounded-2xl border border-base-200/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="is_half_day" name="is_half_day" value="1" class="checkbox checkbox-primary rounded-lg" onchange="toggleHalfDay(this)">
                            <div>
                                <label for="is_half_day" class="text-sm font-bold cursor-pointer text-base-content/80">Partial Day Request</label>
                                <p class="text-[10px] font-medium opacity-50">Select if you are applying for only half a day.</p>
                            </div>
                        </div>
                        
                        <div id="half_day_options" class="hidden">
                            <select name="half_day_type" class="select select-bordered select-sm rounded-xl bg-white border-base-200 font-bold text-xs">
                                <option value="first_half">First Half Session</option>
                                <option value="second_half">Second Half Session</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Reason for Absence</label>
                        <textarea id="reason" name="reason" class="textarea textarea-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100 min-h-[120px]" required placeholder="Briefly describe why you are taking this leave..."></textarea>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="btn btn-primary w-full h-14 rounded-2xl font-black uppercase tracking-widest text-xs shadow-lg shadow-primary/20">
                            Submit Leave Application
                        </button>
                        <div class="flex items-center justify-center gap-2 mt-6 opacity-40">
                            <span class="material-symbols-outlined text-sm">verified_user</span>
                            <span class="text-[9px] font-black uppercase tracking-widest">Formal process &middot; Final approval required</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Info Column --}}
        <div class="space-y-8">
            {{-- Balance Overview --}}
            <div class="bg-base-100 rounded-[32px] shadow-sm border border-base-200 p-8">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-6">Current Balance ({{ now()->year }})</h3>
                <div class="space-y-4">
                    @forelse($balances as $bal)
                        <div class="p-4 rounded-2xl border border-base-200 bg-base-200/20">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-base-content/70">{{ $bal->leaveType->name }}</span>
                                <span class="text-xs font-black text-primary">{{ (float)$bal->balance }} / {{ (float)$bal->total_allocated }}</span>
                            </div>
                            <div class="w-full h-1.5 bg-base-200 rounded-full overflow-hidden">
                                @php 
                                    $percent = ($bal->total_allocated > 0) ? ($bal->total_used / $bal->total_allocated) * 100 : 0;
                                @endphp
                                <div class="h-full bg-primary rounded-full" style="width: {{ 100 - $percent }}%"></div>
                            </div>
                            <p class="text-[9px] font-bold opacity-40 mt-2 uppercase tracking-tighter">{{ (float)$bal->total_used }} Days Used so far</p>
                        </div>
                    @empty
                        <div class="text-center py-8 opacity-40">
                            <span class="material-symbols-outlined text-4xl mb-2">info</span>
                            <p class="text-xs font-bold">No active quotas found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Support Card --}}
            <div class="bg-indigo-600 rounded-[32px] p-8 text-white">
                <h4 class="font-bold text-lg mb-2 tracking-tight">Need Assistance?</h4>
                <p class="text-xs font-medium opacity-80 leading-relaxed mb-6">
                    If you have questions about your leave eligibility or the approval process, contact your HR manager.
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm">support_agent</span>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest">HR Support Center</span>
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
