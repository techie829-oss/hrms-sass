<section class="text-left">
    <header>
        <h2 class="text-xs font-bold uppercase tracking-wider text-on-surface">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-[10px] font-bold text-on-surface-variant opacity-60 uppercase tracking-widest italic">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div class="form-control w-full">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-[10px] font-bold uppercase tracking-wider opacity-60 mb-1" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs font-medium" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-[10px]" />
        </div>

        <div class="form-control w-full">
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-[10px] font-bold uppercase tracking-wider opacity-60 mb-1" />
            <x-text-input id="update_password_password" name="password" type="password" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs font-medium" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-[10px]" />
        </div>

        <div class="form-control w-full">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-[10px] font-bold uppercase tracking-wider opacity-60 mb-1" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs font-medium" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-[10px]" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn btn-primary btn-sm rounded-lg font-bold text-[10px] uppercase tracking-wider px-8 shadow-sm">
                {{ __('Update Security') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-bold text-success uppercase tracking-widest italic"
                >{{ __('Password Updated.') }}</p>
            @endif
        </div>
    </form>
</section>
