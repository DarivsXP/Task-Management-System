<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="font-bold text-slate-700" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="font-bold text-slate-700" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password with Toggle -->
        <div class="mt-4" x-data="{ showPass: false }">
            <x-input-label for="password" :value="__('Password')" class="font-bold text-slate-700" />

            <div class="relative mt-1">
                <input id="password" :type="showPass ? 'text' : 'password'"
                    class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm pr-20 text-sm"
                    name="password"
                    required autocomplete="new-password" />

                <button type="button" @click="showPass = !showPass"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors focus:outline-none">
                    <span x-text="showPass ? 'Hide Password' : 'View Password'">View Password</span>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password with Toggle -->
        <div class="mt-4" x-data="{ showConfirmPass: false }">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="font-bold text-slate-700" />

            <div class="relative mt-1">
                <input id="password_confirmation" :type="showConfirmPass ? 'text' : 'password'"
                    class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm pr-20 text-sm"
                    name="password_confirmation" required autocomplete="new-password" />

                <button type="button" @click="showConfirmPass = !showConfirmPass"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors focus:outline-none">
                    <span x-text="showConfirmPass ? 'Hide Password' : 'View Password'">View Password</span>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-700 shadow-sm transition-all">
                {{ __('Register') }}
            </button>
        </div>
    </form>
</x-guest-layout>
