<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-12 h-12 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="flex flex-col items-center mb-4">
                <p class="font-semibold">LOGIN</p>
                <p class="text-xs">New here? <a href="{{ route('register') }}" class="underline text-blue-600 hover:text-blue-900">{{ __('Register') }}</a></p>
            </div>

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex flex-col items-start justify-center mt-4">

                 <!-- Validation Errors -->
                 <x-auth-validation-errors class="w-full mb-4 border border-red-600 rounded-lg py-2 px-2" :errors="$errors" />

                <x-button class="flex justify-center items-center w-full rounded-full mb-4">
                    {{ __('Login') }}
                </x-button>

                @if (Route::has('password.request'))
                <a class="underline text-xs text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                @endif

               
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>