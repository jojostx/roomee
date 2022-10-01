<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="text-secondary-500 fill-current h-14" />
            </a>
        </x-slot>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="flex flex-col items-center mb-4">
                <p class="font-semibold">CREATE ACCOUNT</p>
                <p class="text-xs">Already have an account? <a href="{{ route('login') }}" class="text-primary-600 underline hover:text-primary-900">{{ __('Log In') }}</a></p>
            </div>
            <div class="grid grid-cols-2 col-span-1 gap-2 mb-2">
                <div class="flex flex-col col-span-1 form_input">
                    <x-label for="firstname" :value="__('First Name')" />
                    <x-input id="firstname" class="block w-full mt-1 text-sm font-medium" type="text" name="firstname" :value="old('firstname')" required autofocus />
                    @error('firstname')
                    <div class="flex items-center mt-1 text-xs text-danger-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="flex flex-col col-span-1 form_input">
                    <x-label for="lastname" :value="__('Last Name')" />
                    <x-input id="lastname" class="block w-full mt-1 text-sm font-medium" type="text" name="lastname" :value="old('lastname')" required autofocus />
                    @error('lastname')
                    <div class="flex items-center mt-1 text-xs text-danger-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <!-- Email Address -->
            <div class="mt-3">
                <x-label for="email" :value="__('Email')" />
                <x-input id="email" class="block w-full mt-1 text-sm font-medium" type="email" name="email" :value="old('email')" required />
                @error('email')
                <div class="flex items-center mt-1 text-xs text-danger-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="relative mt-3" x-data="{ show: true}">
                <x-label for="password" :value="__('Password')" />
                <x-input id="password" class="block w-full mt-1 text-sm font-medium" name="password" required autocomplete="new-password" x-bind:type="show ? 'password' : 'text'" />
                <button class="absolute w-4 text-secondary-600 transform md:w-5 -translate-y-7 right-4" type="button" @click.prevent="show = !show">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <g :class="{ 'hidden' : !show, 'inline-flex' : show }">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </g>
                        <g :class="{ 'hidden': show, 'inline-flex' : !show }">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </g>
                    </svg>
                </button>
                @error('password')
                <div class="flex items-center mt-1 text-xs text-danger-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mt-3 mb-2">
                <x-label for="password_confirmation" :value="__('Confirm Your Password')" />
                <x-input id="password_confirmation" class="block w-full mt-1 text-sm font-medium" type="password" name="password_confirmation" required />
            </div>

            <fieldset class="flex flex-col px-3 py-2 mt-2 mb-2 rounded-md order">
                <label class="mb-1 text-sm text-secondary-600">Choose your Gender:</label>
                <div class="flex">
                    <div>
                        <label for="male" class="mr-1 text-sm">Male</label>
                        <input type="radio" name="gender" id="male" value="male">
                    </div>
                    <div class="ml-6">
                        <label for="male" class="mr-1 text-sm">Female</label>
                        <input type="radio" name="gender" id="female" value="female">
                    </div>
                </div>
            </fieldset>
            @error('gender')
            <div class="flex items-center mt-1 text-xs text-danger-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                {{ $message }}
            </div>
            @enderror

            <div class="mt-3">
                <x-button class="flex items-center justify-center w-full rounded-full">
                    {{ __('Register') }}
                </x-button>
            </div>
            <div class="mt-3">
                <p class="text-xs">By registering, you agree to Roomee's <a href="{{ route('terms') }}" class="text-primary-600 underline hover:text-primary-900">Terms and Conditions</a></p>
            </div>

        </form>
    </x-auth-card>
</x-guest-layout>