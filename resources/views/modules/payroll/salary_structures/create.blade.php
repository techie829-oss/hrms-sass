<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface uppercase tracking-tighter">Assign Structure</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Configure compensation framework for an individual employee.</p>
            </div>
            <div>
                <a href="{{ route('payroll.salary_structures.index') }}" class="btn btn-ghost border-outline-variant/20 rounded-xl font-bold text-xs uppercase tracking-widest px-6">
                    Cancel Assignment
                </a>
            </div>
        </div>
    </x-slot>

    <div x-data="{ 
        ctc: 0,
        earnings: {},
        deductions: {},
        get gross() {
            return Object.values(this.earnings).reduce((a, b) => parseFloat(a || 0) + parseFloat(b || 0), 0);
        },
        get totalDeductions() {
            return Object.values(this.deductions).reduce((a, b) => parseFloat(a || 0) + parseFloat(b || 0), 0);
        },
        get net() {
            return this.gross - this.totalDeductions;
        }
    }" class="max-w-5xl mx-auto">
        <form action="{{ route('payroll.salary_structures.store') }}" method="POST" class="space-y-8 pb-20">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Basic Info -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-surface-container-lowest p-8 rounded-[2.5rem] border border-outline-variant/15 shadow-xl">
                        <h3 class="font-black text-xs uppercase tracking-widest mb-6 opacity-40">Employee Details</h3>
                        
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <x-input-label for="employee_id" :value="__('Select Employee')" />
                                <select name="employee_id" class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-2xl p-4 text-on-surface font-bold uppercase tracking-widest text-xs" required>
                                    <option value="">{{ __('Choose...') }}</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ $employeeId == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="ctc" :value="__('Annual CTC')" />
                                <div class="relative items-center">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-on-surface-variant opacity-30 text-xs">₹</span>
                                    <x-text-input id="ctc" name="ctc" x-model="ctc" type="number" step="0.01" class="block w-full pl-8" required />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="effective_from" :value="__('Effective From')" />
                                <x-text-input id="effective_from" name="effective_from" type="date" class="block w-full" required value="{{ date('Y-m-01') }}" />
                            </div>
                        </div>
                    </div>

                    <!-- Summary Widget -->
                    <div class="bg-primary p-8 rounded-[2.5rem] shadow-xl text-white relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="font-black text-[10px] uppercase tracking-widest opacity-60 mb-8 border-b border-white/10 pb-4">Financial projection</h3>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-end">
                                    <span class="text-[10px] font-black uppercase tracking-widest opacity-60">Gross/Mo</span>
                                    <span class="text-xl font-black tracking-tighter" x-text="'₹' + gross.toLocaleString(undefined, {minimumFractionDigits: 2})"></span>
                                </div>
                                <div class="flex justify-between items-end">
                                    <span class="text-[10px] font-black uppercase tracking-widest opacity-60">Deductions</span>
                                    <span class="text-xl font-black tracking-tighter text-white/50" x-text="'₹' + totalDeductions.toLocaleString(undefined, {minimumFractionDigits: 2})"></span>
                                </div>
                                <div class="mt-8 pt-8 border-t border-white/10 flex justify-between items-end">
                                    <span class="text-[10px] font-black uppercase tracking-widest">Net Payable</span>
                                    <div class="text-right">
                                        <span class="text-3xl font-black tracking-tighter" x-text="'₹' + net.toLocaleString(undefined, {minimumFractionDigits: 2})"></span>
                                        <p class="text-[8px] font-black uppercase tracking-widest opacity-40 mt-1">Take-home monthly</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-9xl opacity-10 rotate-12">payments</span>
                    </div>
                </div>

                <!-- Right: Components -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Earnings -->
                    <div class="bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 shadow-xl overflow-hidden">
                        <div class="bg-surface-container-low px-8 py-4 border-b border-outline-variant/5">
                            <h3 class="font-black text-xs uppercase tracking-widest text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-success text-base">add_circle</span>
                                Earnings List
                            </h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                                @foreach($components->where('type', 'earning') as $comp)
                                    <div class="space-y-2">
                                        <x-input-label for="earnings_{{ $comp->id }}" class="flex justify-between">
                                            <span>{{ $comp->name }}</span>
                                            @if($comp->calculation_type === 'percentage')
                                                <span class="text-[8px] opacity-40 uppercase tracking-tighter">{{ $comp->default_amount }}% of Basic</span>
                                            @endif
                                        </x-input-label>
                                        <div class="relative items-center">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-on-surface-variant opacity-30 text-xs">₹</span>
                                            <x-text-input 
                                                id="earnings_{{ $comp->id }}" 
                                                name="earnings[{{ $comp->id }}]" 
                                                x-model="earnings['{{ $comp->id }}']"
                                                x-init="earnings['{{ $comp->id }}'] = {{ $comp->default_amount }}"
                                                type="number" 
                                                step="0.01" 
                                                class="block w-full pl-8" 
                                            />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Deductions -->
                    <div class="bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 shadow-xl overflow-hidden">
                        <div class="bg-surface-container-low px-8 py-4 border-b border-outline-variant/5">
                            <h3 class="font-black text-xs uppercase tracking-widest text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-error text-base">remove_circle</span>
                                Periodic Deductions
                            </h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                                @foreach($components->where('type', 'deduction') as $comp)
                                    <div class="space-y-2">
                                        <x-input-label for="deductions_{{ $comp->id }}" :value="$comp->name" />
                                        <div class="relative items-center">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-on-surface-variant opacity-30 text-xs">₹</span>
                                            <x-text-input 
                                                id="deductions_{{ $comp->id }}" 
                                                name="deductions[{{ $comp->id }}]" 
                                                x-model="deductions['{{ $comp->id }}']"
                                                x-init="deductions['{{ $comp->id }}'] = {{ $comp->default_amount }}"
                                                type="number" 
                                                step="0.01" 
                                                class="block w-full pl-8 border-error/20 focus:border-error focus:ring-error" 
                                            />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="btn btn-primary bg-gradient-to-r from-primary to-secondary border-none rounded-[2rem] font-black uppercase tracking-[0.3em] text-[10px] h-auto py-5 px-12 shadow-2xl transition-all hover:scale-105 active:scale-95">
                            Commit Structure Assignment
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
