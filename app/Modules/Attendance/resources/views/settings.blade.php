<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Attendance Settings</h2>
                <p class="text-xs font-medium mt-0.5 text-slate-500">Configure daily clock-in enforcement rules and modular exclusions.</p>
            </div>
            <a href="{{ route('attendance.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Logs
            </a>
        </div>
    </x-slot>

    @push('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @endpush

    <div x-data="{ activeTab: '{{ request('tab', 'policies') }}' }" class="space-y-6">
        {{-- Settings Navigation Tabs --}}
        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200 w-fit">
            <button @click="activeTab = 'policies'" :class="activeTab === 'policies' ? 'bg-white shadow-sm text-primary border-slate-200/60' : 'text-slate-500 hover:text-slate-800'" class="px-4 py-1.5 rounded-lg text-xs font-semibold tracking-tight transition-all border border-transparent">
                Attendance Policies
            </button>
            <button @click="activeTab = 'shifts'" :class="activeTab === 'shifts' ? 'bg-white shadow-sm text-primary border-slate-200/60' : 'text-slate-500 hover:text-slate-800'" class="px-4 py-1.5 rounded-lg text-xs font-semibold tracking-tight transition-all border border-transparent">
                Shift Templates
            </button>
        </div>

        {{-- Tab 1: Attendance Policies --}}
        <div x-show="activeTab === 'policies'" x-cloak class="space-y-8">
            <form action="{{ route('attendance.settings.save') }}" method="POST" class="space-y-8">
                @csrf

                <!-- SECTION 1: GLOBAL ENFORCEMENT -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pt-4 first:pt-0">
                    <div class="lg:col-span-1 pr-4">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Global Enforcement</h3>
                        <p class="text-[11px] font-medium text-slate-500 mt-1.5 leading-relaxed">
                            Configure company-wide attendance enforcement rules, access controls, and multi-clocking behaviors.
                        </p>
                    </div>
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 space-y-5">
                            {{-- Enforce Daily Clock-in checkbox --}}
                            <div class="flex items-start gap-3.5">
                                <input type="checkbox" id="enforce_clockin" name="enforce_clockin" class="checkbox checkbox-primary checkbox-sm rounded-md mt-0.5" value="1" {{ old('enforce_clockin', $policy?->enforce_clockin) ? 'checked' : '' }} />
                                <div class="space-y-1">
                                    <label for="enforce_clockin" class="text-xs font-bold text-slate-700 cursor-pointer select-none">Enforce Daily Clock-In (Company Wide)</label>
                                    <p class="text-[11px] font-medium text-slate-500 leading-normal">
                                        When enabled, all company employees are restricted from accessing system features until they clock-in for the day.
                                    </p>
                                </div>
                            </div>

                            <div class="h-px bg-slate-100"></div>

                            {{-- Multi Clock-in select --}}
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-0.5">
                                    <label class="text-xs font-bold text-slate-700 select-none">Default Multi Check-In/Out</label>
                                    <p class="text-[11px] font-medium text-slate-500 leading-normal">
                                        Set the default multi-clocking policy for the organization.
                                    </p>
                                </div>
                                <select name="multi_clocking_policy" class="block w-full sm:w-64 border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold transition-all shadow-sm">
                                    <option value="0" {{ ($policy?->multi_clocking == 0) ? 'selected' : '' }}>Disallow (Once/Day)</option>
                                    <option value="1" {{ ($policy?->multi_clocking == 1) ? 'selected' : '' }}>Allow Multiple</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-slate-200/80"></div>

                <!-- SECTION 2: WORKING HOURS & AUTOMATION -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1 pr-4">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Working Hours & Automation</h3>
                        <p class="text-[11px] font-medium text-slate-500 mt-1.5 leading-relaxed">
                            Define the standard office hours used as fallback when no shift is assigned, and automate checkouts.
                        </p>
                    </div>
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 space-y-6">
                            <!-- Fallback Timing inputs -->
                            <div class="space-y-3">
                                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Standard Fallback Office Timings</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Start Time</label>
                                        <input type="time" name="default_start_time" value="{{ old('default_start_time', $policy?->default_start_time) }}" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold transition-all shadow-sm" />
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">End Time</label>
                                        <input type="time" name="default_end_time" value="{{ old('default_end_time', $policy?->default_end_time) }}" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold transition-all shadow-sm" />
                                    </div>
                                </div>
                            </div>

                            <div class="h-px bg-slate-100"></div>

                            <!-- Auto checkout settings -->
                            <div class="space-y-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3.5">
                                        <input type="checkbox" id="auto_checkout" name="auto_checkout" class="checkbox checkbox-primary checkbox-sm rounded-md mt-0.5" value="1" {{ old('auto_checkout', $policy?->auto_checkout) ? 'checked' : '' }} />
                                        <div class="space-y-1">
                                            <label for="auto_checkout" class="text-xs font-bold text-slate-700 cursor-pointer select-none">Enable Daily Auto-Checkout</label>
                                            <p class="text-[11px] font-medium text-slate-500 leading-normal">
                                                Automatically clock out active sessions daily to prevent missing checkouts.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="pl-7 space-y-1.5 w-full sm:w-64">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Execution Time</label>
                                    <input type="time" name="auto_checkout_time" value="{{ old('auto_checkout_time', $policy?->auto_checkout_time) }}" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold transition-all shadow-sm" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-slate-200/80"></div>

                <!-- SECTION 3: RULES & THRESHOLDS -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1 pr-4">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Rules & Thresholds</h3>
                        <p class="text-[11px] font-medium text-slate-500 mt-1.5 leading-relaxed">
                            Define the rules for calculating half/full days, late marks, early checkouts, and automatic deductions.
                        </p>
                    </div>
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Full Day (Min Hours)</label>
                                    <div class="relative">
                                        <input type="number" name="min_hours_full_day" value="{{ old('min_hours_full_day', $policy?->min_hours_full_day ?? 8) }}" min="1" max="24" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold pr-10 shadow-sm transition-all" />
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-bold text-slate-400 uppercase">hrs</span>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Half Day (Min Hours)</label>
                                    <div class="relative">
                                        <input type="number" name="min_hours_half_day" value="{{ old('min_hours_half_day', $policy?->min_hours_half_day ?? 4) }}" min="1" max="12" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold pr-10 shadow-sm transition-all" />
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-bold text-slate-400 uppercase">hrs</span>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Late Mark After</label>
                                    <div class="relative">
                                        <input type="number" name="late_mark_after_minutes" value="{{ old('late_mark_after_minutes', $policy?->late_mark_after_minutes ?? 15) }}" min="0" max="120" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold pr-10 shadow-sm transition-all" />
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-bold text-slate-400 uppercase">min</span>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Early Leave Before</label>
                                    <div class="relative">
                                        <input type="number" name="early_leave_before_minutes" value="{{ old('early_leave_before_minutes', $policy?->early_leave_before_minutes ?? 30) }}" min="0" max="120" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold pr-10 shadow-sm transition-all" />
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-bold text-slate-400 uppercase">min</span>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Max Late Days/Month</label>
                                    <input type="number" name="max_late_allowed_per_month" value="{{ old('max_late_allowed_per_month', $policy?->max_late_allowed_per_month ?? 3) }}" min="0" max="31" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold shadow-sm transition-all" />
                                </div>
                                <div class="flex flex-col justify-end space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Auto Deduct Leave</label>
                                    <div class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200 h-[34px]">
                                        <input type="checkbox" id="auto_deduct_leave" name="auto_deduct_leave" class="checkbox checkbox-primary checkbox-xs rounded-md" value="1" {{ old('auto_deduct_leave', $policy?->auto_deduct_leave) ? 'checked' : '' }} />
                                        <label for="auto_deduct_leave" class="text-[10px] font-bold text-slate-600 cursor-pointer">Deduct on limit exceed</label>
                                    </div>
                                </div>
                            </div>

                            <div class="h-px bg-slate-100"></div>

                            <!-- Preview Badges -->
                            <div class="space-y-2.5">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status Tags Preview</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Late Arrived
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span> Checkout Missing
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Full Day
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-sky-50 text-sky-700 border border-sky-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Half Day
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-purple-50 text-purple-700 border border-purple-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Overtime
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-orange-50 text-orange-700 border border-orange-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Early Leave
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-slate-200/80"></div>

                <!-- SECTION 4: ROLE-LEVEL DEFAULTS -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1 pr-4">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Role-Level Defaults</h3>
                        <p class="text-[11px] font-medium text-slate-500 mt-1.5 leading-relaxed">
                            Specify enforcement and multi-clocking rules for user roles. These override company-wide policies.
                        </p>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                            <div class="divide-y divide-slate-100">
                                @foreach($roles as $role)
                                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200">
                                            <span class="material-symbols-outlined text-sm">shield_person</span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-xs text-slate-700">{{ $role->name }}</h4>
                                            <p class="text-[10px] font-medium text-slate-400 mt-0.5">Apply settings to all users with this role.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <div class="space-y-1">
                                            <label class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Enforce Mode</label>
                                            <select name="roles[{{ $role->id }}]" class="block w-28 border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-2 py-1 text-[11px] text-slate-700 bg-white font-semibold transition-all shadow-sm">
                                                <option value="0" {{ ($roleEnforcements[$role->id] ?? '0') === '0' ? 'selected' : '' }}>Inherit</option>
                                                <option value="1" {{ ($roleEnforcements[$role->id] ?? '') === '1' ? 'selected' : '' }}>Force</option>
                                                <option value="2" {{ ($roleEnforcements[$role->id] ?? '') === '2' ? 'selected' : '' }}>Exempt</option>
                                            </select>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Multi In/Out</label>
                                            <select name="multi_roles[{{ $role->id }}]" class="block w-28 border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-2 py-1 text-[11px] text-slate-700 bg-white font-semibold transition-all shadow-sm">
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
                </div>

                <div class="h-px bg-slate-200/80"></div>

                <!-- SECTION 5: INDIVIDUAL EMPLOYEE EXCLUSIONS -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1 pr-4">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Employee Exclusions</h3>
                        <p class="text-[11px] font-medium text-slate-500 mt-1.5 leading-relaxed">
                            Set final overrides for specific staff members. Employee settings ignore all company and role-level defaults.
                        </p>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                            <div class="max-h-96 overflow-y-auto divide-y divide-slate-100">
                                @foreach($employees as $employee)
                                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar {{ !$employee->profile_photo ? 'placeholder' : '' }}">
                                            <div class="bg-primary/10 text-primary rounded-xl w-8 h-8 font-bold text-xs border border-primary-200/20 flex items-center justify-center overflow-hidden">
                                                @if($employee->profile_photo)
                                                    <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-xs text-slate-700">{{ $employee->full_name }}</h4>
                                            <p class="text-[10px] font-semibold text-slate-400 mt-0.5">{{ $employee->employee_id }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <div class="space-y-1">
                                            <label class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Enforcement</label>
                                            <select name="employees[{{ $employee->id }}]" class="block w-28 border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-2 py-1 text-[11px] text-slate-700 bg-white font-semibold transition-all shadow-sm">
                                                <option value="0" {{ ($employeeEnforcements[$employee->id] ?? '0') === '0' ? 'selected' : '' }}>Inherit</option>
                                                <option value="1" {{ ($employeeEnforcements[$employee->id] ?? '') === '1' ? 'selected' : '' }}>Force Required</option>
                                                <option value="2" {{ ($employeeEnforcements[$employee->id] ?? '') === '2' ? 'selected' : '' }}>Exempt / Bypass</option>
                                            </select>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Multi In/Out</label>
                                            <select name="multi_employees[{{ $employee->id }}]" class="block w-28 border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-2 py-1 text-[11px] text-slate-700 bg-white font-semibold transition-all shadow-sm">
                                                <option value="0" {{ ($employeeMultiEnforcements[$employee->id] ?? '0') === '0' ? 'selected' : '' }}>Inherit</option>
                                                <option value="1" {{ ($employeeMultiEnforcements[$employee->id] ?? '') === '1' ? 'selected' : '' }}>Allow Multi</option>
                                                <option value="2" {{ ($employeeMultiEnforcements[$employee->id] ?? '') === '2' ? 'selected' : '' }}>Disallow Multi</option>
                                            </select>
                                        </div>
                                        <div class="space-y-1 flex flex-col justify-end h-full">
                                            <label class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider mb-1">Flexible</label>
                                            <div class="flex items-center justify-center bg-slate-50 px-2 py-1 rounded-xl border border-slate-200 h-[26px]">
                                                <input type="checkbox" name="flexible_employees[{{ $employee->id }}]" value="1" class="checkbox checkbox-primary checkbox-xs rounded-md" {{ ($employeeFlexible[$employee->id] ?? false) ? 'checked' : '' }} />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-200">
                    <a href="{{ route('attendance.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-5 shadow-sm text-xs font-semibold">Discard Changes</a>
                    <button type="submit" class="btn btn-primary btn-sm rounded-xl px-6 shadow-sm shadow-primary/20 text-white font-semibold text-xs">
                        Save Attendance Settings
                    </button>
                </div>
            </form>
        </div>

        {{-- Tab 2: Shift Templates --}}
        <div x-show="activeTab === 'shifts'" x-cloak class="space-y-8">
            <!-- SECTION 1: ACTIVE SHIFTS -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pt-4">
                <div class="lg:col-span-1 pr-4">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Active Shift Templates</h3>
                    <p class="text-[11px] font-medium text-slate-500 mt-1.5 leading-relaxed">
                        Manage custom shift schedules. Shift assignments override default fallback rules for specified employees.
                    </p>
                </div>
                <div class="lg:col-span-2 space-y-4">
                    @if($shifts->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($shifts as $shift)
                        <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-slate-200 group hover:border-primary-500/30 transition-all shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 flex-shrink-0">
                                    <span class="material-symbols-outlined text-sm">schedule</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <h4 class="font-bold text-xs text-slate-700">{{ $shift->name }}</h4>
                                        @if($shift->is_default) 
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase tracking-wider">Default</span> 
                                        @endif
                                        @if($shift->is_overnight) 
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60 uppercase tracking-wider">Overnight</span> 
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-500 font-semibold mt-1">
                                        {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}
                                        → {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
                                        &nbsp;·&nbsp; {{ $shift->grace_minutes }}m grace
                                        &nbsp;·&nbsp; {{ $shift->half_day_hours }}h half
                                        @if($shift->min_hours_full_day)
                                            &nbsp;·&nbsp; <span class="text-primary font-bold">{{ $shift->min_hours_full_day }}h full</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <form action="{{ route('attendance.shifts.delete', $shift) }}" method="POST" onsubmit="return confirm('Delete this shift?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-7 h-7 rounded-lg bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center hover:bg-rose-100 transition-colors opacity-0 group-hover:opacity-100">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="bg-white border border-slate-200 border-dashed rounded-xl p-8 text-center">
                        <span class="material-symbols-outlined text-3xl text-slate-300">calendar_add_on</span>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-2">No shift templates created yet</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="h-px bg-slate-200/80"></div>

            <!-- SECTION 2: CREATE SHIFT -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1 pr-4">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Create Shift Template</h3>
                    <p class="text-[11px] font-medium text-slate-500 mt-1.5 leading-relaxed">
                        Define a new shift timing configuration with custom grace time and minimum working hour requirements.
                    </p>
                </div>
                <div class="lg:col-span-2">
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
                        <form action="{{ route('attendance.shifts.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Shift Name</label>
                                    <input type="text" name="name" placeholder="e.g. Morning Shift" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold transition-all shadow-sm placeholder-slate-400" required />
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Start Time</label>
                                    <input type="time" name="start_time" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold transition-all shadow-sm" required />
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">End Time</label>
                                    <input type="time" name="end_time" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold transition-all shadow-sm" required />
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Grace (min)</label>
                                    <input type="number" name="grace_minutes" value="15" min="0" max="120" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold transition-all shadow-sm" required />
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Half Day (hrs)</label>
                                    <input type="number" name="half_day_hours" value="4" min="1" max="12" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold transition-all shadow-sm" required />
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Full Day Min (hrs) <span class="text-slate-400 normal-case font-medium ml-1">(opt)</span></label>
                                    <input type="number" name="min_hours_full_day" placeholder="e.g. 8" min="1" max="24" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold transition-all shadow-sm placeholder-slate-400" />
                                </div>
                            </div>

                            <div class="flex items-center gap-6 pt-2">
                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" name="is_default" class="checkbox checkbox-primary checkbox-xs rounded-md" />
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Set Default Shift</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" name="is_overnight" class="checkbox checkbox-primary checkbox-xs rounded-md" />
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Overnight Shift</span>
                                </label>
                            </div>

                            <div class="flex justify-end pt-4 border-t border-slate-100">
                                <button type="submit" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20 text-white font-semibold text-xs flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm">add</span> Create Shift
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
