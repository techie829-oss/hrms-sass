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
                            </div>
                        </div>
                        @foreach($employees as $employee)
                            <div class="flex items-center justify-between gap-4 bg-surface-container-low p-4 rounded-2xl border border-outline-variant/10 hover:border-tertiary/20 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-tertiary/10 text-tertiary rounded-xl w-10 h-10 font-bold text-xs border border-tertiary/10 group-hover:bg-tertiary group-hover:text-white transition-colors">
                                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
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
</x-app-layout>
