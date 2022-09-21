<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="text-gray-500 fill-current h-14" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="flex flex-col items-center mb-4">
                <p class="font-semibold">LOGIN</p>
                <p class="text-xs">New here? <a href="{{ route('register') }}" class="underline text-primary-600 hover:text-primary-900">{{ __('Register') }}</a></p>
            </div>

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="text-indigo-600 border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex flex-col items-start justify-center mt-4">

                 <!-- Validation Errors -->
                 <x-auth-validation-errors class="w-full px-2 py-2 mb-4 border border-red-600 rounded-lg" :errors="$errors" />

                <x-button class="flex items-center justify-center w-full mb-4 rounded-full">
                    {{ __('Login') }}
                </x-button>

                @if (Route::has('password.request'))
                <a class="text-xs text-gray-600 underline hover:text-gray-900" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                @endif

               
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>