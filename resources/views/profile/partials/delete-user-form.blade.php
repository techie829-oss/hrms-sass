<section class="space-y-6 text-left">
    <header>
        <h2 class="text-xs font-bold uppercase tracking-wider text-error">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-[10px] font-bold text-on-surface-variant opacity-60 uppercase tracking-widest italic leading-relaxed">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        class="btn btn-error btn-sm rounded-lg font-bold text-[10px] uppercase tracking-wider px-8 shadow-sm text-white"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Terminate Account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('tenant.profile.destroy') }}" class="p-0 overflow-hidden bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-2xl font-sans">
            @csrf
            @method('delete')

            <div class="p-5 border-b border-outline-variant/5 bg-error/5">
                <h2 class="text-xs font-bold uppercase tracking-wider text-error">
                    {{ __('Confirm Account Termination') }}
                </h2>
                <p class="text-[9px] font-bold opacity-50 uppercase tracking-widest mt-0.5 text-error">
                    {{ __('This action is irreversible') }}
                </p>
            </div>

            <div class="p-6 space-y-5 text-left">
                <p class="text-xs font-medium text-on-surface-variant leading-relaxed italic opacity-70">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="form-control w-full">
                    <x-input-label for="password" value="{{ __('Password') }}" class="text-[10px] font-bold uppercase tracking-wider opacity-60 mb-1" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="input input-sm input-bordered focus:input-error rounded-lg text-xs font-medium w-full"
                        placeholder="{{ __('Enter password to confirm...') }}"
                    />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1 text-[10px]" />
                </div>
            </div>

            <div class="p-5 bg-surface-container-low/30 border-t border-outline-variant/5 flex justify-end gap-3">
                <button type="button" class="btn btn-ghost btn-sm rounded-lg font-bold text-[10px] uppercase tracking-wider" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="btn btn-error btn-sm rounded-lg font-bold text-[10px] uppercase tracking-wider px-8 shadow-sm text-white">
                    {{ __('Permanently Delete') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
