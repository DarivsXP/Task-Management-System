<section>
    <header>
        <h2 class="text-xl font-bold text-slate-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div x-data="{ showCurrent: false }">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="font-bold text-slate-700" />
            <div class="relative mt-1">
                <input id="update_password_current_password" name="current_password" :type="showCurrent ? 'text' : 'password'"
                    class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm pr-20 text-sm"
                    autocomplete="current-password" />
                <button type="button" @click="showCurrent = !showCurrent"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors focus:outline-none">
                    <span x-text="showCurrent ? 'Hide Password' : 'View Password'">View Password</span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div x-data="{ showNew: false }">
            <x-input-label for="update_password_password" :value="__('New Password')" class="font-bold text-slate-700" />
            <div class="relative mt-1">
                <input id="update_password_password" name="password" :type="showNew ? 'text' : 'password'"
                    class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm pr-20 text-sm"
                    autocomplete="new-password" />
                <button type="button" @click="showNew = !showNew"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors focus:outline-none">
                    <span x-text="showNew ? 'Hide Password' : 'View Password'">View Password</span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div x-data="{ showConfirm: false }">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="font-bold text-slate-700" />
            <div class="relative mt-1">
                <input id="update_password_password_confirmation" name="password_confirmation" :type="showConfirm ? 'text' : 'password'"
                    class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm pr-20 text-sm"
                    autocomplete="new-password" />
                <button type="button" @click="showConfirm = !showConfirm"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors focus:outline-none">
                    <span x-text="showConfirm ? 'Hide Password' : 'View Password'">View Password</span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-700 shadow-sm transition-all">
                {{ __('Save Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 4000)"
                    class="text-sm font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200"
                >{{ __('Password successfully updated!') }}</p>
            @endif
        </div>
    </form>
</section>
