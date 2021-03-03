<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-12 h-12 text-gray-500 fill-current" />
            </a>
        </x-slot>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="flex flex-col items-center mb-6">
                <p class="font-semibold">CREATE ACCOUNT</p>
                <p class="text-xs">Already have an account? <a href="{{ route('login') }}" class="text-blue-600 underline hover:text-blue-900">{{ __('Log In') }}</a></p>
            </div>

            <div class="flex justify-between mb-6">
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

            <!-- FirstName -->
            <div>
                <x-input id="firstname" class="block w-full mt-1 text-sm font-medium" placeholder="First Name" type="text" name="firstname" :value="old('firstname')" required autofocus />
            </div>

            <!-- LastName -->
            <div class="mt-4">
                <x-input id="lastname" class="block w-full mt-1 text-sm font-medium" placeholder="Last Name" type="text" name="lastname" :value="old('lastname')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input id="email" class="block w-full mt-1 text-sm font-medium" placeholder="Email Address" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input id="password" class="block w-full mt-1 text-sm font-medium" placeholder="Password" type="password" name="password" required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4 mb-2">
                <x-input id="password_confirmation" class="block w-full mt-1 text-sm font-medium" placeholder="Confirm Password" type="password" name="password_confirmation" required />
            </div>

            <!-- Validation Errors -->
            <x-auth-validation-errors class="px-2 py-2 mt-2 mb-4 border border-red-600 rounded-lg" :errors="$errors" />

            <div class="mt-4">
                <x-button class="flex items-center justify-center w-full rounded-full">
                    {{ __('Register') }}
                </x-button>
            </div>
            <div class="mt-4">
                <p class="text-xs">By registering, you agree to Roomee's <a href="{{ route('terms') }}" class="text-blue-600 underline hover:text-blue-900">Terms and Conditions</a></p>
            </div>
            
        </form>



    </x-auth-card>
</x-guest-layout>