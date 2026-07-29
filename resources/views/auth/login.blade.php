<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-bold text-slate-700" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password with Show/Hide Toggle -->
        <div class="mt-4" x-data="{ showPassword: false }">
            <x-input-label for="password" :value="__('Password')" class="font-bold text-slate-700" />

            <div class="relative mt-1">
                <input id="password" :type="showPassword ? 'text' : 'password'"
                    class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm pr-20 text-sm"
                    name="password"
                    required autocomplete="current-password" />

                <button type="button" @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors focus:outline-none">
                    <span x-text="showPassword ? 'Hide Password' : 'View Password'">View Password</span>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm font-medium text-slate-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-700 shadow-sm transition-all">
                {{ __('Log in') }}
            </button>
        </div>

        @if (Route::has('register'))
            <div class="mt-8 pt-6 border-t border-slate-200 text-center">
                <p class="text-sm font-medium text-slate-500">Don't have an account?</p>
                <a href="{{ route('register') }}"
                    class="mt-3 inline-flex items-center justify-center w-full px-5 py-2.5 border-2 border-indigo-600 text-indigo-600 rounded-lg font-bold text-sm hover:bg-indigo-50 transition-all">
                    Create an Account
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
