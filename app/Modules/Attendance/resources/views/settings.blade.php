<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface uppercase">Attendance Settings</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Configure daily clock-in enforcement rules and modular exclusions.</p>
            </div>
            <a href="{{ route('attendance.index') }}" class="btn btn-ghost btn-sm">
                <span class="material-symbols-outlined">arrow_back</span> Back to Logs
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8">
        <form action="{{ route('attendance.settings.save') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Card 1: Company-Wide Setting -->
            <div class="card bg-surface-container-lowest shadow-xl border border-outline-variant/15 rounded-[2.5rem] overflow-hidden">
                <div class="card-body p-8 md:p-10 space-y-6">
                    <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-4">
                        <span class="material-symbols-outlined text-primary text-3xl">corporate_fare</span>
                        <div>
                            <h3 class="font-black font-headline text-lg uppercase tracking-wider text-on-surface">Company-Wide Enforcement</h3>
                            <p class="text-[10px] opacity-70 font-medium">Define general clock-in requirement rules for the entire organization.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 bg-primary/5 p-6 rounded-3xl border border-primary/10">
                        <input type="checkbox" id="enforce_clockin" name="enforce_clockin" class="checkbox checkbox-primary rounded-xl mt-1" value="1" {{ old('enforce_clockin', $policy?->enforce_clockin) ? 'checked' : '' }} />
                        <div>
                            <label for="enforce_clockin" class="font-black text-sm uppercase tracking-wider text-primary cursor-pointer select-none">Enforce Daily Clock-In (Company Wide)</label>
                            <p class="text-xs opacity-80 mt-1 font-medium leading-relaxed">
                                When enabled, all company employees are restricted from accessing system features until they clock-in for the day. 
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 bg-secondary/5 p-6 rounded-3xl border border-secondary/10">
                        <div class="flex flex-col gap-4 w-full">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="font-black text-sm uppercase tracking-wider text-secondary select-none">Default Multi Check-In/Out</label>
                                    <p class="text-xs opacity-80 mt-1 font-medium leading-relaxed">
                                        Set the default multi-clocking policy for the organization.
                                    </p>
                                </div>
                                <select name="multi_clocking_policy" class="select select-bordered select-sm w-40 text-[10px] font-black uppercase tracking-wider rounded-xl bg-surface-container-lowest">
                                    <option value="0" {{ ($policy?->multi_clocking == 0) ? 'selected' : '' }}>Disallow (Once/Day)</option>
                                    <option value="1" {{ ($policy?->multi_clocking == 1) ? 'selected' : '' }}>Allow Multiple</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Working Hours & Shift Rules -->
            <div class="card bg-surface-container-lowest shadow-xl border border-outline-variant/15 rounded-[2.5rem] overflow-hidden">
                <div class="card-body p-8 md:p-10 space-y-8">
                    <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-4">
                        <span class="material-symbols-outlined text-tertiary text-3xl">schedule</span>
                        <div>
                            <h3 class="font-black font-headline text-lg uppercase tracking-wider text-on-surface">Working Hours & Shift Rules</h3>
                            <p class="text-[10px] opacity-70 font-medium">Configure standard office hours and automatic check-out behaviors.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Office Hours -->
                        <div class="space-y-4">
                            <h4 class="text-[10px] font-black uppercase tracking-widest opacity-40">Standard Office Timing</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-wider opacity-60">Start Time</label>
                                    <input type="time" name="default_start_time" value="{{ old('default_start_time', $policy?->default_start_time) }}" class="input input-bordered w-full rounded-2xl font-black text-xs bg-surface-container-low" />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-wider opacity-60">End Time</label>
                                    <input type="time" name="default_end_time" value="{{ old('default_end_time', $policy?->default_end_time) }}" class="input input-bordered w-full rounded-2xl font-black text-xs bg-surface-container-low" />
                                </div>
                            </div>
                            <p class="text-[10px] opacity-50 font-medium italic">* This will be used as the default if no specific shift is assigned.</p>
                        </div>

                        <!-- Auto-Checkout -->
                        <div class="space-y-4">
                            <h4 class="text-[10px] font-black uppercase tracking-widest opacity-40">System Automation</h4>
                            <div class="bg-surface-container-low p-6 rounded-[2rem] border border-outline-variant/10 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="auto_checkout" name="auto_checkout" class="checkbox checkbox-tertiary rounded-lg" value="1" {{ old('auto_checkout', $policy?->auto_checkout) ? 'checked' : '' }} />
                                        <label for="auto_checkout" class="font-black text-[11px] uppercase tracking-wider text-on-surface cursor-pointer">Enable Auto-Checkout</label>
                                    </div>
                                    <div class="badge badge-tertiary font-black text-[8px] uppercase tracking-tighter">Automatic</div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-wider opacity-60">Execution Time</label>
                                    <input type="time" name="auto_checkout_time" value="{{ old('auto_checkout_time', $policy?->auto_checkout_time) }}" class="input input-bordered input-sm w-full rounded-xl font-black text-xs bg-surface-container-lowest" />
                                </div>
                                <p class="text-[9px] opacity-60 font-medium leading-relaxed">The system will automatically clock-out any active sessions at this specific time every day.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card C: Attendance Rules & Thresholds -->
            <div class="card bg-surface-container-lowest shadow-xl border border-outline-variant/15 rounded-[2.5rem] overflow-hidden">
                <div class="card-body p-8 md:p-10 space-y-8">
                    <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-4">
                        <span class="material-symbols-outlined text-warning text-3xl">tune</span>
                        <div>
                            <h3 class="font-black font-headline text-lg uppercase tracking-wider text-on-surface">Attendance Rules & Thresholds</h3>
                            <p class="text-[10px] opacity-70 font-medium">Define full day, half day, late mark, and leave deduction rules.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-wider opacity-50">Full Day (Min Hours)</label>
                            <div class="relative">
                                <input type="number" name="min_hours_full_day" value="{{ old('min_hours_full_day', $policy?->min_hours_full_day ?? 8) }}" min="1" max="24" class="input input-bordered w-full rounded-2xl font-black text-sm bg-surface-container-low pr-12" />
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black opacity-40 uppercase">hrs</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-wider opacity-50">Half Day (Min Hours)</label>
                            <div class="relative">
                                <input type="number" name="min_hours_half_day" value="{{ old('min_hours_half_day', $policy?->min_hours_half_day ?? 4) }}" min="1" max="12" class="input input-bordered w-full rounded-2xl font-black text-sm bg-surface-container-low pr-12" />
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black opacity-40 uppercase">hrs</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-wider opacity-50">Late Mark After</label>
                            <div class="relative">
                                <input type="number" name="late_mark_after_minutes" value="{{ old('late_mark_after_minutes', $policy?->late_mark_after_minutes ?? 15) }}" min="0" max="120" class="input input-bordered w-full rounded-2xl font-black text-sm bg-surface-container-low pr-12" />
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black opacity-40 uppercase">min</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-wider opacity-50">Early Leave Before</label>
                            <div class="relative">
                                <input type="number" name="early_leave_before_minutes" value="{{ old('early_leave_before_minutes', $policy?->early_leave_before_minutes ?? 30) }}" min="0" max="120" class="input input-bordered w-full rounded-2xl font-black text-sm bg-surface-container-low pr-12" />
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black opacity-40 uppercase">min</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-wider opacity-50">Max Late Days/Month</label>
                            <input type="number" name="max_late_allowed_per_month" value="{{ old('max_late_allowed_per_month', $policy?->max_late_allowed_per_month ?? 3) }}" min="0" max="31" class="input input-bordered w-full rounded-2xl font-black text-sm bg-surface-container-low" />
                        </div>
                        <div class="flex flex-col justify-end space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-wider opacity-50">Auto Deduct Leave</label>
                            <div class="flex items-center gap-3 bg-surface-container-low p-4 rounded-2xl border border-outline-variant/10 h-[52px]">
                                <input type="checkbox" id="auto_deduct_leave" name="auto_deduct_leave" class="checkbox checkbox-warning checkbox-sm rounded-lg" value="1" {{ old('auto_deduct_leave', $policy?->auto_deduct_leave) ? 'checked' : '' }} />
                                <label for="auto_deduct_leave" class="text-[10px] font-black uppercase tracking-wider cursor-pointer">When exceeded</label>
                            </div>
                        </div>
                    </div>

                    <!-- Smart Tag Badges Preview -->
                    <div class="pt-4 border-t border-outline-variant/10">
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-3">Computed Tag Badges Preview</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-300">
                                <span class="material-symbols-outlined text-xs">schedule</span> Late Arrived
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-100 text-red-800 border border-red-300 animate-pulse">
                                <span class="material-symbols-outlined text-xs">warning</span> Checkout Missing
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-green-100 text-green-800 border border-green-300">
                                <span class="material-symbols-outlined text-xs">check_circle</span> Full Day
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-800 border border-blue-300">
                                <span class="material-symbols-outlined text-xs">circle_half</span> Half Day
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-300">
                                <span class="material-symbols-outlined text-xs">more_time</span> Overtime
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-orange-100 text-orange-800 border border-orange-300">
                                <span class="material-symbols-outlined text-xs">exit_to_app</span> Early Leave
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card D: Shift Management -->
            <div class="card bg-surface-container-lowest shadow-xl border border-outline-variant/15 rounded-[2.5rem] overflow-hidden">
                <div class="card-body p-8 md:p-10 space-y-6">
                    <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-4">
                        <span class="material-symbols-outlined text-success text-3xl">work_history</span>
                        <div>
                            <h3 class="font-black font-headline text-lg uppercase tracking-wider text-on-surface">Shift Management</h3>
                            <p class="text-[10px] opacity-70 font-medium">Create and manage work shifts. Assign them to employees from their profile.</p>
                        </div>
                    </div>

                    <!-- Existing Shifts List -->
                    @if($shifts->isNotEmpty())
                    <div class="space-y-2">
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-40">Active Shifts</p>
                        @foreach($shifts as $shift)
                        <div class="flex items-center justify-between bg-surface-container-low p-4 rounded-2xl border border-outline-variant/10 group hover:border-success/30 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center border border-success/20">
                                    <span class="material-symbols-outlined text-sm text-success">schedule</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-black text-xs text-on-surface">{{ $shift->name }}</h4>
                                        @if($shift->is_default) <span class="badge badge-xs badge-success font-black uppercase tracking-tighter">Default</span> @endif
                                        @if($shift->is_overnight) <span class="badge badge-xs badge-warning font-black uppercase tracking-tighter">Overnight</span> @endif
                                    </div>
                                    <p class="text-[10px] opacity-60 font-mono mt-0.5">
                                        {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}
                                        → {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
                                        &nbsp;·&nbsp; {{ $shift->grace_minutes }}min grace
                                        &nbsp;·&nbsp; {{ $shift->half_day_hours }}h half-day
                                        @if($shift->min_hours_full_day)
                                            &nbsp;·&nbsp; <span class="text-info font-black">{{ $shift->min_hours_full_day }}h full-day</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <form action="{{ route('attendance.shifts.delete', $shift) }}" method="POST" onsubmit="return confirm('Delete this shift?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-xs text-error opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-6 opacity-40">
                        <span class="material-symbols-outlined text-4xl">calendar_add_on</span>
                        <p class="text-xs font-black uppercase tracking-widest mt-2">No shifts created yet</p>
                    </div>
                    @endif

                    <!-- Add New Shift Form -->
                    <div class="bg-surface-container-low p-6 rounded-[2rem] border border-dashed border-success/30">
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-50 mb-4">Add New Shift</p>
                        <form action="{{ route('attendance.shifts.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="col-span-2 md:col-span-1 space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-wider opacity-50">Shift Name</label>
                                    <input type="text" name="name" placeholder="e.g. Morning Shift" class="input input-bordered input-sm w-full rounded-xl font-bold text-xs bg-surface-container-lowest" required />
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-wider opacity-50">Start Time</label>
                                    <input type="time" name="start_time" class="input input-bordered input-sm w-full rounded-xl font-bold text-xs bg-surface-container-lowest" required />
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-wider opacity-50">End Time</label>
                                    <input type="time" name="end_time" class="input input-bordered input-sm w-full rounded-xl font-bold text-xs bg-surface-container-lowest" required />
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-wider opacity-50">Grace (min)</label>
                                    <input type="number" name="grace_minutes" value="15" min="0" max="120" class="input input-bordered input-sm w-full rounded-xl font-bold text-xs bg-surface-container-lowest" required />
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-wider opacity-50">Half Day (hrs)</label>
                                    <input type="number" name="half_day_hours" value="4" min="1" max="12" class="input input-bordered input-sm w-full rounded-xl font-bold text-xs bg-surface-container-lowest" required />
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-wider opacity-50">Full Day Min (hrs)
                                        <span class="text-info/70 normal-case font-medium ml-1">(optional)</span>
                                    </label>
                                    <input type="number" name="min_hours_full_day" placeholder="e.g. 8" min="1" max="24" class="input input-bordered input-sm w-full rounded-xl font-bold text-xs bg-surface-container-lowest" />
                                </div>
                                <div class="flex items-end gap-6 pb-1">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_default" class="checkbox checkbox-success checkbox-xs rounded" />
                                        <span class="text-[9px] font-black uppercase tracking-wider opacity-60">Set Default</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_overnight" class="checkbox checkbox-warning checkbox-xs rounded" />
                                        <span class="text-[9px] font-black uppercase tracking-wider opacity-60">Overnight</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-end pt-4">
                                <button type="submit" class="btn btn-success btn-sm rounded-xl font-black uppercase tracking-widest text-[10px] px-8 shadow-lg shadow-success/20">
                                    <span class="material-symbols-outlined text-sm">add</span> Create Shift
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Card 2: Role-Level Enforcements -->
            <div class="card bg-surface-container-lowest shadow-xl border border-outline-variant/15 rounded-[2.5rem] overflow-hidden">
                <div class="card-body p-8 md:p-10 space-y-6">
                    <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-4">
                        <span class="material-symbols-outlined text-secondary text-3xl">shield_person</span>
                        <div>
                            <h3 class="font-black font-headline text-lg uppercase tracking-wider text-on-surface">Role-Level Defaults</h3>
                            <p class="text-[10px] opacity-70 font-medium">Specify which user roles require daily clock-in (effective if company-wide is disabled).</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <div class="flex items-center justify-between px-6 opacity-40">
                            <span class="text-[8px] font-black uppercase tracking-widest">Role Name</span>
                            <div class="flex items-center gap-6">
                                <span class="text-[8px] font-black uppercase tracking-widest w-32 text-center">Enforce Mode</span>
                                <span class="text-[8px] font-black uppercase tracking-widest w-32 text-center">Multi In/Out</span>
                            </div>
                        </div>
                        @foreach($roles as $role)
                            <div class="flex items-center justify-between bg-surface-container-low p-5 rounded-2xl border border-outline-variant/10 hover:border-secondary/20 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-secondary/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-sm text-secondary">person</span>
                                    </div>
                                    <div>
                                        <label for="role_{{ $role->id }}" class="font-black text-xs uppercase tracking-wider text-on-surface cursor-pointer select-none group-hover:text-secondary transition-colors">{{ $role->name }}</label>
                                        <p class="text-[10px] opacity-60">Apply settings to all users with this role.</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-6">
                                    <div class="w-32 flex justify-center">
                                        <select name="roles[{{ $role->id }}]" class="select select-bordered select-sm w-full text-[10px] font-black uppercase tracking-wider rounded-xl bg-surface-container-lowest">
                                            <option value="0" {{ ($roleEnforcements[$role->id] ?? '0') === '0' ? 'selected' : '' }}>Inherit</option>
                                            <option value="1" {{ ($roleEnforcements[$role->id] ?? '') === '1' ? 'selected' : '' }}>Force</option>
                                            <option value="2" {{ ($roleEnforcements[$role->id] ?? '') === '2' ? 'selected' : '' }}>Exempt</option>
                                        </select>
                                    </div>
                                    <div class="w-32 flex justify-center">
                                        <select name="multi_roles[{{ $role->id }}]" class="select select-bordered select-sm w-full text-[10px] font-black uppercase tracking-wider rounded-xl bg-surface-container-lowest">
                                            <option value="0" {{ ($multiRoleEnforcements[$role->id] ?? '0') === '0' ? 'selected' : '' }}>Inherit</option>
                                            <option value="1" {{ ($multiRoleEnforcements[$role->id] ?? '') === '1' ? 'selected' : '' }}>Allow</option>
                                            <option value="2" {{ ($multiRoleEnforcements[$role->id] ?? '') === '2' ? 'selected' : '' }}>Disallow</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Card 3: Employee-Level Exclusions & Overrides -->
            <div class="card bg-surface-container-lowest shadow-xl border border-outline-variant/15 rounded-[2.5rem] overflow-hidden">
                <div class="card-body p-8 md:p-10 space-y-6">
                    <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-4">
                        <span class="material-symbols-outlined text-tertiary text-3xl">badge</span>
                        <div>
                            <h3 class="font-black font-headline text-lg uppercase tracking-wider text-on-surface">Employee Overrides & Exemptions</h3>
                            <p class="text-[10px] opacity-70 font-medium">Explicitly exempt or force require clock-in for individual employees, overriding general company and role defaults.</p>
                        </div>
                    </div>

                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                        <div class="flex items-center justify-between px-6 opacity-40 mb-2">
                            <span class="text-[8px] font-black uppercase tracking-widest">Employee</span>
                            <div class="flex items-center gap-8">
                                <span class="text-[8px] font-black uppercase tracking-widest w-40 text-center">Enforcement Mode</span>
                                <span class="text-[8px] font-black uppercase tracking-widest w-40 text-center">Multi In/Out</span>
                                <span class="text-[8px] font-black uppercase tracking-widest w-24 text-center">Flexible</span>
                            </div>
                        </div>
                        @foreach($employees as $employee)
                            <div class="flex items-center justify-between gap-4 bg-surface-container-low p-4 rounded-2xl border border-outline-variant/10 hover:border-tertiary/20 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="avatar {{ !$employee->profile_photo ? 'placeholder' : '' }}">
                                        <div class="bg-tertiary/10 text-tertiary rounded-xl w-10 h-10 font-bold text-xs border border-tertiary/10 group-hover:bg-tertiary group-hover:text-white transition-colors overflow-hidden">
                                            @if($employee->profile_photo)
                                                <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-xs text-on-surface group-hover:text-tertiary transition-colors">{{ $employee->full_name }}</h4>
                                        <p class="text-[10px] opacity-60 tracking-wider font-bold">{{ $employee->employee_id }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-6">
                                    <div class="w-40">
                                        <select name="employees[{{ $employee->id }}]" class="select select-bordered select-sm w-full text-[10px] font-black uppercase tracking-wider rounded-xl bg-surface-container-lowest">
                                            <option value="0" {{ ($employeeEnforcements[$employee->id] ?? '0') === '0' ? 'selected' : '' }}>Inherit Default</option>
                                            <option value="1" {{ ($employeeEnforcements[$employee->id] ?? '') === '1' ? 'selected' : '' }}>Force Required</option>
                                            <option value="2" {{ ($employeeEnforcements[$employee->id] ?? '') === '2' ? 'selected' : '' }}>Exempt / Bypass</option>
                                        </select>
                                    </div>
                                    <div class="w-40">
                                        <select name="multi_employees[{{ $employee->id }}]" class="select select-bordered select-sm w-full text-[10px] font-black uppercase tracking-wider rounded-xl bg-surface-container-lowest">
                                            <option value="0" {{ ($employeeMultiEnforcements[$employee->id] ?? '0') === '0' ? 'selected' : '' }}>Inherit Default</option>
                                            <option value="1" {{ ($employeeMultiEnforcements[$employee->id] ?? '') === '1' ? 'selected' : '' }}>Allow Multi</option>
                                            <option value="2" {{ ($employeeMultiEnforcements[$employee->id] ?? '') === '2' ? 'selected' : '' }}>Disallow Multi</option>
                                        </select>
                                    </div>
                                    {{-- Flexible Toggle: only min_hours matters, no late/early leave penalty --}}
                                    <div class="w-24 flex justify-center">
                                        <label class="flex flex-col items-center gap-1 cursor-pointer">
                                            <input type="checkbox"
                                                name="flexible_employees[{{ $employee->id }}]"
                                                value="1"
                                                class="checkbox checkbox-info checkbox-sm rounded-lg"
                                                {{ ($employeeFlexible[$employee->id] ?? false) ? 'checked' : '' }} />
                                            <span class="text-[8px] font-black uppercase tracking-wider text-info/60">Hours Only</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('attendance.index') }}" class="btn btn-ghost rounded-2xl font-black uppercase tracking-widest text-xs px-8">Discard</a>
                <button type="submit" class="btn btn-primary bg-gradient-to-br from-primary to-tertiary border-none rounded-2xl font-black uppercase tracking-[0.2em] text-xs h-auto py-5 px-12 shadow-xl hover:scale-105 transition-transform">
                    Save Enforcement Rules
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    function toggleFlexible(checkbox) {
        const field = document.getElementById('flexible_hours_field');
        if (field) field.classList.toggle('hidden', !checkbox.checked);
    }
    </script>
    @endpush
</x-app-layout>
