<section class="text-left">
    <header class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-xs font-black uppercase tracking-wider text-on-surface">
                {{ __('Profile Information & Emergency Contact') }}
            </h2>
            <p class="mt-1 text-[9px] font-bold text-on-surface-variant opacity-50 uppercase tracking-widest italic leading-relaxed">
                {{ __("Update your contact details and emergency response information.") }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('tenant.profile.update') }}" class="space-y-8">
        @csrf
        @method('patch')

        {{-- Account Basics --}}
        <div class="space-y-5">
            <h3 class="text-[10px] font-black text-primary uppercase tracking-tighter flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-primary/40"></span> Account Basics
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="form-control w-full">
                    <x-input-label for="name" :value="__('Display Name')" class="text-[9px] font-bold uppercase tracking-wider opacity-60 mb-1" />
                    <x-text-input id="name" name="name" type="text" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs font-medium" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-1 text-[10px]" :messages="$errors->get('name')" />
                </div>

                <div class="form-control w-full">
                    <x-input-label for="email" :value="__('Email Address')" class="text-[9px] font-bold uppercase tracking-wider opacity-60 mb-1" />
                    <x-text-input id="email" name="email" type="email" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs font-medium" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-1 text-[10px]" :messages="$errors->get('email')" />
                </div>
            </div>
        </div>

        {{-- Personal Details --}}
        <div class="space-y-5">
            <h3 class="text-[10px] font-black text-secondary uppercase tracking-tighter flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-secondary/40"></span> Personal Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="form-control w-full">
                    <x-input-label for="phone" :value="__('Phone Number')" class="text-[9px] font-bold uppercase tracking-wider opacity-60 mb-1" />
                    <x-text-input id="phone" name="phone" type="text" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs font-medium" :value="old('phone', $user->employee?->phone ?? '')" placeholder="+91 ..." />
                    <x-input-error class="mt-1 text-[10px]" :messages="$errors->get('phone')" />
                </div>

                <div class="form-control w-full">
                    <x-input-label for="gender" :value="__('Gender')" class="text-[9px] font-bold uppercase tracking-wider opacity-60 mb-1" />
                    <select id="gender" name="gender" class="select select-sm select-bordered focus:select-primary rounded-lg text-xs font-medium">
                        <option value="" disabled selected>Select...</option>
                        <option value="male" {{ (old('gender', $user->employee?->gender ?? '') === 'male') ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ (old('gender', $user->employee?->gender ?? '') === 'female') ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ (old('gender', $user->employee?->gender ?? '') === 'other') ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-input-error class="mt-1 text-[10px]" :messages="$errors->get('gender')" />
                </div>

                <div class="form-control w-full">
                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" class="text-[9px] font-bold uppercase tracking-wider opacity-60 mb-1" />
                    <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs font-medium" :value="old('date_of_birth', ($user->employee?->date_of_birth ?? null) ? $user->employee->date_of_birth->format('Y-m-d') : '')" />
                    <x-input-error class="mt-1 text-[10px]" :messages="$errors->get('date_of_birth')" />
                </div>
            </div>
        </div>

        {{-- Emergency Contact --}}
        <div class="space-y-5">
            <h3 class="text-[10px] font-black text-accent uppercase tracking-tighter flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-accent/40"></span> Emergency Response
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 bg-surface-container-low/30 p-5 rounded-2xl border border-outline-variant/5">
                <div class="form-control w-full">
                    <x-input-label for="emergency_contact_name" :value="__('Contact Name')" class="text-[9px] font-bold uppercase tracking-wider opacity-60 mb-1" />
                    <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs font-medium" :value="old('emergency_contact_name', $user->employee?->emergency_contact_name ?? '')" placeholder="Next of kin..." />
                    <x-input-error class="mt-1 text-[10px]" :messages="$errors->get('emergency_contact_name')" />
                </div>

                <div class="form-control w-full">
                    <x-input-label for="emergency_contact_phone" :value="__('Emergency Phone')" class="text-[9px] font-bold uppercase tracking-wider opacity-60 mb-1" />
                    <x-text-input id="emergency_contact_phone" name="emergency_contact_phone" type="text" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs font-medium" :value="old('emergency_contact_phone', $user->employee?->emergency_contact_phone ?? '')" placeholder="+91 ..." />
                    <x-input-error class="mt-1 text-[10px]" :messages="$errors->get('emergency_contact_phone')" />
                </div>

                <div class="form-control w-full lg:col-span-1 md:col-span-2">
                    <x-input-label for="emergency_contact_relation" :value="__('Relationship')" class="text-[9px] font-bold uppercase tracking-wider opacity-60 mb-1" />
                    <x-text-input id="emergency_contact_relation" name="emergency_contact_relation" type="text" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs font-medium" :value="old('emergency_contact_relation', $user->employee?->emergency_contact_relation ?? '')" placeholder="Spouse, Parent, etc." />
                    <x-input-error class="mt-1 text-[10px]" :messages="$errors->get('emergency_contact_relation')" />
                </div>
            </div>
        </div>

        {{-- Address Details --}}
        <div class="space-y-5">
            <h3 class="text-[10px] font-black text-info uppercase tracking-tighter flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-info/40"></span> Address Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="form-control w-full">
                    <x-input-label for="current_address" :value="__('Current Address')" class="text-[9px] font-bold uppercase tracking-wider opacity-60 mb-1" />
                    <textarea id="current_address" name="current_address" class="textarea textarea-sm textarea-bordered focus:textarea-primary rounded-lg text-xs font-medium h-20" placeholder="Street, City, State, ZIP...">{{ old('current_address', $user->employee?->current_address ?? '') }}</textarea>
                    <x-input-error class="mt-1 text-[10px]" :messages="$errors->get('current_address')" />
                </div>

                <div class="form-control w-full">
                    <x-input-label for="permanent_address" :value="__('Permanent Address')" class="text-[9px] font-bold uppercase tracking-wider opacity-60 mb-1" />
                    <textarea id="permanent_address" name="permanent_address" class="textarea textarea-sm textarea-bordered focus:textarea-primary rounded-lg text-xs font-medium h-20" placeholder="Same as above or different...">{{ old('permanent_address', $user->employee?->permanent_address ?? '') }}</textarea>
                    <x-input-error class="mt-1 text-[10px]" :messages="$errors->get('permanent_address')" />
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-outline-variant/5">
            <button type="submit" class="btn btn-primary btn-sm rounded-lg font-bold text-[10px] uppercase tracking-wider px-10 shadow-sm shadow-primary/20">
                {{ __('Sync Profile Details') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div class="flex items-center gap-2" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                    <span class="material-symbols-outlined text-success text-sm">check_circle</span>
                    <p class="text-[10px] font-bold text-success uppercase tracking-widest italic">{{ __('Changes synchronized.') }}</p>
                </div>
            @endif
        </div>
    </form>
</section>
