<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="fill-current text-secondary-500 h-14" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="flex flex-col items-center">
                <p class="text-lg font-semibold">LOGIN</p>
                <p class="text-sm">New here? <a href="{{ route('register') }}" class="underline text-primary-600 hover:text-primary-900">{{ __('Register') }}</a></p>
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="text-primary-600 rounded shadow-sm border-secondary-300 focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-secondary-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex flex-col items-start justify-center mt-4">
                 <!-- Validation Errors -->
                 <x-auth-validation-errors class="w-full px-2 py-2 mb-4 border rounded-lg border-danger-600" :errors="$errors" />

                <x-button class="flex items-center justify-center w-full rounded-full">
                    {{ __('Login') }}
                </x-button>

                @if (Route::has('password.request'))
                <a class="mt-4 text-sm underline text-primary-600 hover:text-primary-900" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                @endif
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>