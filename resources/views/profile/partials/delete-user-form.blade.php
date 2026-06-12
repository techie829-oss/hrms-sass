<section class="space-y-6 text-left">
    <header>
        <h2 class="text-lg font-bold text-red-600">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        class="inline-flex justify-center py-2 px-6 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ url('/profile') }}" class="p-0 overflow-hidden bg-white rounded-xl border border-slate-200 shadow-2xl font-sans">
            @csrf
            @method('delete')

            <div class="p-6 border-b border-slate-100 bg-red-50/50">
                <h2 class="text-lg font-bold text-slate-900">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>
            </div>

            <div class="p-6 space-y-5 text-left">
                <div>
                    <x-input-label for="password" value="{{ __('Password') }}" class="text-slate-700 font-semibold sr-only" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-full focus:border-red-500 focus:ring-red-500/20"
                        placeholder="{{ __('Password') }}"
                    />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" class="inline-flex justify-center py-2 px-4 border border-slate-300 rounded-lg shadow-sm text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
