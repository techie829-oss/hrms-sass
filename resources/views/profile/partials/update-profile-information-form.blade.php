<section class="text-left">
    <header class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-lg font-bold text-slate-900">
                {{ __('Profile Information & Emergency Contact') }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                {{ __("Update your contact details and emergency response information.") }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ url('/profile') }}" class="space-y-8">
        @csrf
        @method('patch')

        {{-- Account Basics --}}
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span> Account Basics
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="name" :value="__('Display Name')" class="text-slate-700 font-semibold" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="text-slate-700 font-semibold" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>
            </div>
        </div>

        {{-- Personal Details --}}
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Personal Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <x-input-label for="phone" :value="__('Phone Number')" class="text-slate-700 font-semibold" />
                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->employee?->phone ?? '')" placeholder="+91 ..." />
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                </div>

                <div>
                    <x-input-label for="gender" :value="__('Gender')" class="text-slate-700 font-semibold" />
                    <select id="gender" name="gender" class="mt-1 block w-full bg-white border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 transition-all shadow-sm">
                        <option value="" disabled selected>Select...</option>
                        <option value="male" {{ (old('gender', $user->employee?->gender ?? '') === 'male') ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ (old('gender', $user->employee?->gender ?? '') === 'female') ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ (old('gender', $user->employee?->gender ?? '') === 'other') ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                </div>

                <div>
                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" class="text-slate-700 font-semibold" />
                    <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" :value="old('date_of_birth', ($user->employee?->date_of_birth ?? null) ? $user->employee->date_of_birth->format('Y-m-d') : '')" />
                    <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                </div>
            </div>
        </div>

        {{-- Emergency Contact --}}
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Emergency Response
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-slate-50 p-6 rounded-xl border border-slate-100">
                <div>
                    <x-input-label for="emergency_contact_name" :value="__('Contact Name')" class="text-slate-700 font-semibold" />
                    <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text" class="mt-1 block w-full" :value="old('emergency_contact_name', $user->employee?->emergency_contact_name ?? '')" placeholder="Next of kin..." />
                    <x-input-error class="mt-2" :messages="$errors->get('emergency_contact_name')" />
                </div>

                <div>
                    <x-input-label for="emergency_contact_phone" :value="__('Emergency Phone')" class="text-slate-700 font-semibold" />
                    <x-text-input id="emergency_contact_phone" name="emergency_contact_phone" type="text" class="mt-1 block w-full" :value="old('emergency_contact_phone', $user->employee?->emergency_contact_phone ?? '')" placeholder="+91 ..." />
                    <x-input-error class="mt-2" :messages="$errors->get('emergency_contact_phone')" />
                </div>

                <div class="lg:col-span-1 md:col-span-2">
                    <x-input-label for="emergency_contact_relation" :value="__('Relationship')" class="text-slate-700 font-semibold" />
                    <x-text-input id="emergency_contact_relation" name="emergency_contact_relation" type="text" class="mt-1 block w-full" :value="old('emergency_contact_relation', $user->employee?->emergency_contact_relation ?? '')" placeholder="Spouse, Parent, etc." />
                    <x-input-error class="mt-2" :messages="$errors->get('emergency_contact_relation')" />
                </div>
            </div>
        </div>

        {{-- Address Details --}}
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Address Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="current_address" :value="__('Current Address')" class="text-slate-700 font-semibold" />
                    <textarea id="current_address" name="current_address" class="mt-1 block w-full bg-white border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm h-24" placeholder="Street, City, State, ZIP...">{{ old('current_address', $user->employee?->current_address ?? '') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('current_address')" />
                </div>

                <div>
                    <x-input-label for="permanent_address" :value="__('Permanent Address')" class="text-slate-700 font-semibold" />
                    <textarea id="permanent_address" name="permanent_address" class="mt-1 block w-full bg-white border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm h-24" placeholder="Same as above or different...">{{ old('permanent_address', $user->employee?->permanent_address ?? '') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('permanent_address')" />
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-slate-100">
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div class="flex items-center gap-2" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                    <span class="material-symbols-outlined text-green-600 text-sm">check_circle</span>
                    <p class="text-sm font-medium text-green-600">{{ __('Changes saved.') }}</p>
                </div>
            @endif
        </div>
    </form>
</section>
