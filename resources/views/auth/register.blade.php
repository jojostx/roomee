<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="text-gray-500 fill-current h-14" />
            </a>
        </x-slot>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="flex flex-col items-center mb-4">
                <p class="font-semibold">CREATE ACCOUNT</p>
                <p class="text-xs">Already have an account? <a href="{{ route('login') }}" class="text-blue-600 underline hover:text-blue-900">{{ __('Log In') }}</a></p>
            </div>

            <div class="flex justify-between mb-4">
                <x-Oauth-social-link class="w-1/2">
                    <x-slot name="OAuthLogo">
                        <svg width="18px" height="19px" viewbox="0 0 18 19" version="1.1" xmlns="http://www.w3.org/2000/svg">
                            <g id="search">
                                <path d="M3.98918 6.01664L3.36263 8.35566L1.07258 8.40411C0.388196 7.13472 0 5.68238 0 4.13902C0 2.6466 0.362953 1.23922 1.00631 0L1.00681 0L3.04559 0.373782L3.9387 2.40033C3.75177 2.94529 3.64989 3.53029 3.64989 4.13902C3.64996 4.79967 3.76963 5.43266 3.98918 6.01664L3.98918 6.01664Z" transform="translate(0 4.860962)" id="Shape" fill="#FBBB00" fill-rule="evenodd" stroke="none" />
                                <path d="M8.64489 0C8.74825 0.54443 8.80215 1.10668 8.80215 1.68131C8.80215 2.32566 8.7344 2.95418 8.60534 3.56045C8.16722 5.62353 7.02243 7.425 5.43657 8.69984L5.43607 8.69935L2.86812 8.56832L2.50467 6.29951C3.55697 5.68238 4.37935 4.7166 4.81254 3.56045L0 3.56045L0 0L4.88275 0L8.64489 0L8.64489 0L8.64489 0Z" transform="translate(9.197266 7.3187256)" id="Shape" fill="#518EF8" fill-rule="evenodd" stroke="none" />
                                <path d="M13.5613 5.14037L13.5618 5.14087C12.0195 6.38058 10.0602 7.12234 7.92742 7.12234C4.5 7.12234 1.52012 5.20665 0 2.38746L2.9166 0C3.67664 2.02845 5.63341 3.47242 7.92742 3.47242C8.91345 3.47242 9.83722 3.20587 10.6299 2.74054L13.5613 5.14037L13.5613 5.14037Z" transform="translate(1.0722656 10.877686)" id="Shape" fill="#28B446" fill-rule="evenodd" stroke="none" />
                                <path d="M13.7384 2.07197L10.8228 4.45894C10.0024 3.94615 9.03263 3.64992 7.99369 3.64992C5.64775 3.64992 3.65439 5.16013 2.93242 7.26132L0.000492186 4.86099L0 4.86099C1.49787 1.97308 4.51533 0 7.99369 0C10.1774 0 12.1797 0.777868 13.7384 2.07197L13.7384 2.07197Z" transform="translate(1.0058594 0)" id="Shape" fill="#F14336" fill-rule="evenodd" stroke="none" />
                            </g>
                        </svg>
                    </x-slot>
                    <p class="ml-2 text-sm font-bold">Google</p>
                </x-Oauth-social-link>
                <x-Oauth-social-link class="w-1/2 ml-4">
                    <x-slot name="OAuthLogo">
                        <svg width="18px" height="18px" viewbox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.00005 1.05588e-07C4.02951 1.05588e-07 0 4.02951 0 9.00005C0 13.458 3.2447 17.15 7.49902 17.8649L7.49902 10.8777L5.32795 10.8777L5.32795 8.36329L7.49902 8.36329L7.49902 6.50925C7.49902 4.35804 8.81292 3.18575 10.7322 3.18575C11.6515 3.18575 12.4414 3.25425 12.6708 3.28442L12.6708 5.53301L11.3396 5.53366C10.296 5.53366 10.0948 6.02945 10.0948 6.75726L10.0948 8.362L12.5849 8.362L12.2601 10.8764L10.0948 10.8764L10.0948 17.9251C14.5478 17.3831 18 13.5971 18 8.99748C18 4.02951 13.9705 1.05588e-07 9.00005 1.05588e-07L9.00005 1.05588e-07Z" id="Shape" fill="#3B5998" fill-rule="evenodd" stroke="none" />
                        </svg>
                    </x-slot>
                    <p class="ml-2 text-sm font-bold">Facebook</p>
                </x-Oauth-social-link>
            </div>

            <div class="grid grid-cols-2 col-span-1 gap-2 mb-2">
                <div class="flex flex-col col-span-1 form_input">
                    <x-label for="firstname" :value="__('First Name')" />
                    <x-input id="firstname" class="block w-full mt-1 text-sm font-medium" type="text" name="firstname" :value="old('firstname')" required autofocus />
                    @error('firstname')
                    <div class="flex items-center mt-1 text-xs text-red-500">
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
                    <div class="flex items-center mt-1 text-xs text-red-500">
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
                <div class="flex items-center mt-1 text-xs text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="relative mt-3" x-data="{ show: true}">
                <x-label for="password" :value="__('Password')" />
                <x-input id="password" class="block w-full mt-1 text-sm font-medium" name="password" required autocomplete="new-password" x-bind:type="show ? 'password' : 'text'" />
                <button class="absolute w-4 text-gray-600 transform md:w-5 -translate-y-7 right-4" @click.prevent="show = !show">
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
                <div class="flex items-center mt-1 text-xs text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mt-3 mb-2">
                <x-label for="password_confirmation" :value="__('Confirm Your Password')" />
                <x-input id="password_confirmation" class="block w-full mt-1 text-sm font-medium" type="password" name="password_confirmation" required />
            </div>

            <fieldset class="flex flex-col px-3 py-2 mt-4 mb-2 border rounded-md">
                <label class="mb-1 text-sm text-gray-600">Choose your Gender:</label>
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
            <div class="flex items-center mt-1 text-xs text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                {{ $message }}</div>
            @enderror

            <div class="mt-3">
                <x-button class="flex items-center justify-center w-full rounded-full">
                    {{ __('Register') }}
                </x-button>
            </div>
            <div class="mt-3">
                <p class="text-xs">By registering, you agree to Roomee's <a href="{{ route('terms') }}" class="text-blue-600 underline hover:text-blue-900">Terms and Conditions</a></p>
            </div>

        </form>
    </x-auth-card>
</x-guest-layout>