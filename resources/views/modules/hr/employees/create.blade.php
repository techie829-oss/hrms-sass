<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('hr.employees.index') }}" class="btn btn-ghost btn-sm btn-square rounded-xl">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface uppercase">Onboard Architect</h2>
                <p class="text-sm text-on-surface-variant font-medium">Curate a new member into your organizational sanctuary.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('hr.employees.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Personal Section -->
            <div class="bg-surface-container-lowest p-8 md:p-12 rounded-[3rem] premium-shadow border border-outline-variant/15 space-y-10">
                <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-6">
                    <span class="material-symbols-outlined text-primary text-3xl">person</span>
                    <h3 class="font-black font-headline text-xl uppercase tracking-widest text-on-surface">Personal Intelligence</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <x-input-label for="first_name" :value="__('First Name')" />
                        <x-text-input id="first_name" name="first_name" class="block w-full" type="text" required placeholder="Julian" />
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="last_name" :value="__('Last Name')" />
                        <x-text-input id="last_name" name="last_name" class="block w-full" type="text" required placeholder="Sterling" />
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <x-input-label for="email" :value="__('Professional Email')" />
                        <x-text-input id="email" name="email" class="block w-full" type="email" required placeholder="julian@company.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="employee_id" :value="__('Unique Identifier (ID)')" />
                        <x-text-input id="employee_id" name="employee_id" class="block w-full uppercase" type="text" required placeholder="EMP-001" />
                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Employment Section -->
            <div class="bg-surface-container-low p-8 md:p-12 rounded-[3rem] premium-shadow border border-outline-variant/10 space-y-10">
                <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-6">
                    <span class="material-symbols-outlined text-secondary text-3xl">work</span>
                    <h3 class="font-black font-headline text-xl uppercase tracking-widest text-on-surface">Structural Role</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <x-input-label for="date_of_joining" :value="__('Curation Date (Joining)')" />
                        <x-text-input id="date_of_joining" name="date_of_joining" class="block w-full" type="date" required />
                        <x-input-error :messages="$errors->get('date_of_joining')" class="mt-2" />
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="employment_type" :value="__('Contract Type')" />
                        <select id="employment_type" name="employment_type" class="w-full bg-surface-container-lowest border-transparent focus:border-primary focus:ring-0 rounded-xl p-3 text-on-surface transition-all font-medium">
                            <option value="full_time">Full Time Sanctuary</option>
                            <option value="part_time">Part Time Engagement</option>
                            <option value="contract">Project Contractor</option>
                            <option value="intern">Apprentice / Intern</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <x-input-label for="status" :value="__('Initial Status')" />
                        <select id="status" name="status" class="w-full bg-surface-container-lowest border-transparent focus:border-primary focus:ring-0 rounded-xl p-3 text-on-surface transition-all font-medium">
                            <option value="active">Active Presence</option>
                            <option value="on_leave">Sanctuary Absence</option>
                            <option value="inactive">Inactive Record</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="basic_salary" :value="__('Architectural Value (Salary)')" />
                        <div class="flex items-center">
                            <span class="bg-surface-container-lowest border-r-0 border border-transparent p-3 rounded-l-xl font-bold text-on-surface-variant">₹</span>
                            <x-text-input id="basic_salary" name="basic_salary" class="block w-full rounded-l-none" type="number" step="0.01" required placeholder="0.00" />
                        </div>
                        <x-input-error :messages="$errors->get('basic_salary')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('hr.employees.index') }}" class="btn btn-ghost rounded-2xl font-black uppercase tracking-widest text-xs px-10">Cancel</a>
                <button type="submit" class="btn btn-primary primary-gradient border-none rounded-2xl font-black uppercase tracking-[0.2em] text-xs h-auto py-5 px-12 shadow-xl hover:scale-105 transition-transform">
                    Confirm Onboarding
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
