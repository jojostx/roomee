<x-landing-layout>
    <!-- Hero Section -->

    <x-slot name="banner">
        <div class="relative flex items-center justify-center h-screen mb-4 bg-gray-900 hero hero-bg">
            <div class="absolute w-full h-full blur-bg"></div>

            <div class="relative z-20 flex flex-col items-center justify-start w-11/12 pt-0 md:w-5/6 lg:flex-row lg:items-start lg:justify-between lg:pt-0 ">
                <div class="flex-shrink-0 max-w-md mb-16 -mt-24 text-center text-gray-200 lg:self-center lg:text-left lg:mb-8 ">
                    <h1 class="px-2 mb-6 text-3xl font-bold lg:px-0 sm:text-4xl">Finding A Roommate Has Never Been Easier</h1>
                    <h2 class="mb-6 text-base font-semibold sm:text-xl">Searching for a good roommate is like looking for a soulmate, very few people do find them. Roomee helps you find the perfect roommate based on your preferences.</h2>
                    <x-ctaButton href="#" class="font-bold text-gray-100 bg-blue-500 hover:text-gray-100 hover:bg-blue-600">
                        LEARN MORE
                    </x-ctaButton>
                    <x-ctaButton href="{{ route('register') }}" class="font-bold text-gray-200 bg-transparent border-gray-200 sm:ml-6 xs:ml-2 sm:mt-0 hover:text-gray-900 hover:bg-white hover:border-white">
                        GET STARTED
                    </x-ctaButton>
                </div>
                <x-auth-form>
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

                        <!-- FirstName -->
                        <div>
                            <x-input id="firstname" class="block w-full mt-1 text-sm font-medium" placeholder="First Name" type="text" name="firstname" :value="old('firstname')" required />
                        </div>

                        <!-- LastName -->
                        <div class="mt-4">
                            <x-input id="lastname" class="block w-full mt-1 text-sm font-medium" placeholder="Last Name" type="text" name="lastname" :value="old('lastname')" required />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input id="email" class="block w-full mt-1 text-sm font-medium" placeholder="Email Address" type="email" name="email" :value="old('email')" required />
                        </div>

                        <!-- Password -->
                        <div class="relative mt-4" x-data="{ show: true}">
                            <x-input id="password" class="block w-full mt-1 text-sm font-medium" placeholder="Password" name="password" required autocomplete="new-password" x-bind:type="show ? 'password' : 'text'" />
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
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4 mb-2">
                            <x-input id="password_confirmation" class="block w-full mt-1 text-sm font-medium" placeholder="Confirm Password" type="password" name="password_confirmation" required />
                        </div>

                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="px-2 py-2 mt-2 mb-4 border border-red-600 rounded-lg" :errors="$errors" />

                        <div class="mt-4">
                            <x-button class="flex items-center justify-center w-full font-bold rounded-full hover:bg-blue-500">
                                {{ __('Register') }}
                            </x-button>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs">By registering, you agree to Roomee's <a href="{{ route('terms') }}" class="text-blue-600 underline hover:text-blue-900">Terms and Conditions</a></p>
                        </div>

                    </form>
                </x-auth-form>
            </div>

            <div class="absolute bottom-0 left-0 z-0 w-full text-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 160">
                    <path fill="currentColor" fill-opacity="1" d="M0,64L120,74.7C240,85,480,107,720,101.3C960,96,1200,64,1320,48L1440,32L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path>
                </svg>
            </div>
        </div>
    </x-slot>

    <!-- invisible div (background) for the form when it is in a stacking context with the banner text  -->
    <div class="h-80 sm:h-80 md:h-96 lg:h-12">
    </div>

    <!-- End of Hero Section -->

    <!-- How it works section -->
    <div id="howItWorks" class="relative z-20 flex flex-col items-center justify-center w-full pt-16 pb-8">
        <div class="pb-12 text-xl font-bold text-center sm:pb-16 xs:text-2xl sm:text-3xl">
            <h1>HOW IT WORKS</h1>
            <div class="flex items-center justify-center w-full mt-4">
                <i class="mt-4 -mr-2 text-red-500 animate-spin-slow animate-delay-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-12">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                </i>
                <i class="text-blue-500 animate-reverseSpin animate-delay-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-12">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                </i>
            </div>
        </div>
        <div class="relative z-20 w-11/12 max-w-full mx-auto">
            <div class="grid w-full grid-cols-1 pt-0 md:grid-cols-2">
                <div class="flex flex-col items-center w-full md:items-end">
                    <x-steps>
                        <x-slot name="icon">
                            <div class="p-2 mr-6 bg-blue-200 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                </svg>
                            </div>
                        </x-slot>
                        <div>
                            <p class="mb-2 text-base font-bold text-blue-900 sm:text-lg">CREATE ACCOUNT</p>
                            <p class="max-w-sm text-sm sm:text-base">Create a Roomee account. Upload a recent profile photo and display photos. Carefully fill in your preferences, educational info and budget.</p>
                        </div>
                    </x-steps>
                    <x-steps>
                        <x-slot name="icon">
                            <div class="p-2 mr-6 bg-red-200 rounded-xl">
                                <svg class="w-10 text-red-500" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor" fill-rule="evenodd">
                                    <path d="M8.89371 0.551933C8.72415 0.213618 8.37814 0 7.99971 0C7.62128 0 7.27527 0.213618 7.10571 0.551933L0.105712 14.5519C-0.0717616 14.9066 -0.0228425 15.3327 0.230403 15.6379C0.48365 15.9432 0.893355 16.0699 1.27471 15.9609L6.27471 14.5319C6.70407 14.4091 6.99996 14.0165 6.99971 13.5699L6.99971 8.99893C6.99971 8.64167 7.19031 8.31154 7.49971 8.13291C7.80911 7.95428 8.19031 7.95428 8.49971 8.13291C8.80911 8.31154 8.99971 8.64167 8.99971 8.99893L8.99971 13.5699C8.9995 14.0166 9.29541 14.4092 9.72478 14.532L14.7247 15.9599C15.1059 16.0691 15.5157 15.9427 15.7691 15.6377C16.0226 15.3327 16.0718 14.9067 15.8947 14.5519L8.89471 0.551935L8.89371 0.551933Z" transform="matrix(0.9999999 0 0 0.9999999 2 2.0019531)" />
                                </svg>
                            </div>
                        </x-slot>
                        <div>
                            <p class="mb-2 text-base font-bold text-red-800 sm:text-lg">SEND A REQUEST</p>
                            <p class="max-w-sm text-sm sm:text-base">Pick a roommate that fits your preferences from Roomee's suggested roommates page and send them a roommate request.</p>
                        </div>
                    </x-steps>
                    <x-steps>
                        <x-slot name="icon">
                            <div class="p-2 mr-6 bg-yellow-200 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                                </svg>
                            </div>
                        </x-slot>
                        <div>
                            <p class="mb-2 text-base font-bold text-yellow-800 sm:text-lg">DISCUSS CONDITIONS</p>
                            <p class="max-w-sm text-sm sm:text-base">When the user accepts your roommate request, you can chat with the matched user to discuss conditions and reach an agreement.</p>
                        </div>
                    </x-steps>
                    <x-steps>
                        <x-slot name="icon">
                            <div class="p-2 mr-6 bg-green-200 rounded-xl">
                                <svg class="w-10 text-green-600" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor" fill-rule="evenodd" stroke="none">
                                    <g id="Group" transform="translate(0 4.166641)">
                                        <path d="M4.83332 0.91668C4.16664 0.25 1.33332 0.0833592 0.416641 0C0.33332 0 0.249961 0 0.166641 0.0833204C0.0833203 0.16668 0 0.333359 0 0.41668L0 7.91668C0 8.16668 0.16668 8.33336 0.41668 8.33336L2.91668 8.33336C3.08336 8.33336 3.25 8.25004 3.33336 8.08336C3.33336 7.83336 4.83336 3.41668 5.00004 1.25004C5 1.16668 5 1.00004 4.83332 0.91668L4.83332 0.91668Z" />
                                    </g>
                                    <g id="Group" transform="translate(7.0722656 5.9166718)">
                                        <path d="M6.67729 1.16666C5.84397 0.916657 5.09398 0.583336 4.42729 0.333337C2.92729 -0.333343 2.26062 1.60933e-05 0.927294 1.33334C0.343974 1.91666 -0.0727057 2.75002 0.010615 3.08334C0.010615 3.16666 0.010615 3.16666 0.177295 3.25002C0.593975 3.4167 1.17729 3.50002 1.76062 2.58334C1.84394 2.50002 1.92729 2.41666 2.09394 2.41666C2.34394 2.41666 2.42726 2.33334 2.67726 2.24998C2.84394 2.16666 3.01058 2.0833 3.26058 1.99998C3.3439 1.99998 3.3439 1.99998 3.42726 1.99998C3.51058 1.99998 3.67726 2.0833 3.76058 2.0833C4.1773 2.50002 4.9273 3.08334 5.67729 3.75002C6.84397 4.6667 8.01062 5.6667 8.59397 6.4167L8.67729 6.4167C8.09398 4.75002 7.09397 1.91666 6.67729 1.16666L6.67729 1.16666Z" />
                                    </g>
                                    <g id="Group" transform="translate(14.167969 5)">
                                        <path d="M5.41668 0C2.16668 0 0.333359 0.833321 0.25 0.833321C0.16668 0.916641 0.0833201 1 0 1.08332C0 1.16664 0 1.33332 0.0833201 1.41664C0.583319 2.24996 2.25 6.83332 2.5 7.99996C2.58332 8.16664 2.75 8.33328 2.91668 8.33328L5.41668 8.33328C5.66668 8.33328 5.83336 8.1666 5.83336 7.9166L5.83336 0.416602C5.83336 0.16668 5.66668 0 5.41668 0L5.41668 0Z" />
                                    </g>
                                    <g id="Group" transform="translate(4.3339844 6.6666718)">
                                        <path d="M10.75 6.5C10.4166 5.75 8.99996 4.66668 7.74996 3.66668C7.08332 3.08332 6.41664 2.58332 5.91664 2.16664C5.83332 2.24996 5.66664 2.24996 5.66664 2.33332C5.41664 2.41664 5.33332 2.5 4.99996 2.5C4.33332 3.33332 3.49996 3.66664 2.58332 3.33332C2.16664 3.25 1.91664 2.91664 1.83332 2.58332C1.66664 1.75 2.41664 0.583321 3 0L1.33332 0C1 1.66668 0.416641 3.83332 0 5C0.33332 5.33332 0.66668 5.75 0.91668 5.91668C2.5 7.25 4.33336 8.58336 4.66668 8.83336C4.91668 9.00004 5.41668 9.16668 5.66668 9.16668C5.75 9.16668 5.83336 9.16668 5.91668 9.16668L4.58332 7.83332C4.41664 7.66664 4.41664 7.41664 4.58332 7.25C4.75 7.08336 5 7.08332 5.16664 7.25L6.83332 8.91668C7 9.08336 7.16664 9 7.33332 9C7.58332 8.91668 7.66664 8.75 7.75 8.5L5.83332 6.58332C5.66664 6.41664 5.66664 6.16664 5.83332 6C6 5.83336 6.25 5.83332 6.41664 6L8.49996 8.08332C8.58328 8.16664 8.91664 8.16664 9.16664 8.08332C9.24996 8 9.41664 7.91664 9.49996 7.75L7.16664 5.41664C6.99996 5.24996 6.99996 4.99996 7.16664 4.83332C7.33332 4.66668 7.58332 4.66664 7.74996 4.83332L10.1666 7.25C10.3333 7.33332 10.5 7.25 10.6666 7.16668C10.75 7.08332 10.9166 6.83332 10.75 6.5L10.75 6.5Z" />
                                    </g>
                                </svg>
                            </div>
                        </x-slot>
                        <div>
                            <p class="mb-2 text-base font-bold text-green-800 sm:text-lg">MEET IN PERSON</p>
                            <p class="max-w-sm text-sm sm:text-base">Meet up, preferably at a public spot and during the day. Finalize the agreements by signing an agreement letter. And voila!!! you've gotten a roommate.</p>
                        </div>
                    </x-steps>
                </div>
                <div class="relative items-center justify-center hidden w-full h-full md:flex">
                    <div class="relative">
                        <div class="relative top-0 left-0 z-20 min-w-max">
                            <svg xmlns="http://www.w3.org/2000/svg" class="max-w-lg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 717 817.62">
                                <defs>
                                    <style>
                                        .cls-1 {
                                            stroke: #000;
                                            stroke-miterlimit: 10;
                                            stroke-width: 4px;
                                        }
                                    </style>
                                </defs>
                                <g id="Layer_1-2">
                                    <image width="717" height="140" transform="translate(0 677.62)" xlink:href="data:image/png;base64,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" />

                                    <path class="cls-1" d="M448.13,2H233a54.25,54.25,0,0,0-54.24,54.24V664A54.25,54.25,0,0,0,233,718.22H484.54A54.24,54.24,0,0,0,538.78,664V56.24A54.24,54.24,0,0,0,484.54,2ZM524,663.89a40.56,40.56,0,0,1-40.57,40.56H234.13a40.56,40.56,0,0,1-40.56-40.56V56.32a40.56,40.56,0,0,1,40.56-40.56H269v9.06A18.05,18.05,0,0,0,287,42.88H430.07a18.05,18.05,0,0,0,18.06-18.06V15.76h35.3A40.56,40.56,0,0,1,524,56.32Z" />
                                </g>
                            </svg>
                        </div>

                        <div class="top-0 w-64 mx-auto overflow-hidden transform -translate-x-1/2 rounded-3xl left-1/2 steps-carousel">
                            <div class="w-64 ">
                                <img src="{{ asset('images/screen.png') }}" alt="sign up">
                            </div>
                            <div class="w-64 ">
                                <img src="{{ asset('images/screen.png') }}" alt="sign up">
                            </div>
                            <div class="w-64 ">
                                <img src="{{ asset('images/screen.png') }}" alt="sign up">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 z-0 w-full text-blue-400">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <!-- <defs>
                    <linearGradient id="grad1" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:rgb(96,165,250);stop-opacity:1" />
                        <stop offset="100%" style="stop-color:rgb(17,24,39);stop-opacity:1" />
                    </linearGradient>
                </defs> -->
                <path fill="currentColor"  fill-opacity="1" d="M0,128L48,117.3C96,107,192,85,288,90.7C384,96,480,128,576,154.7C672,181,768,203,864,186.7C960,171,1056,117,1152,90.7C1248,64,1344,64,1392,64L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </div>
    <!-- End of How it Works Section -->

    <!-- CTA section -->
    <div class="flex flex-col items-center justify-center w-full px-16 pt-6 pb-16 text-center bg-blue-400 md:py-16 md:pb-24 md:pt-10">
        <p class="mb-6 text-3xl font-bold text-gray-800">Ready to find the perfect roommate?</p>
        <x-ctaButton href=" {{ route('register') }}" class="px-12 py-4 text-lg font-bold leading-3 text-white bg-gray-800 rounded-full md:text-xl hover:bg-blue-800">Sign Up</x-ctaButton>
    </div>
    <!-- End of CTA section -->

    <!-- Carousel Section -->
    <section class="py-10 bg-blue-100 lg:py-24">
        <div class="flex flex-col items-center justify-center">
            <p class="mb-4 text-2xl font-semibold">Lots of happy users</p>
            <div class="flex mb-4">
                <div class="mx-2 animate-bounce animate-delay-2 w-7">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <circle cx="256" cy="256" r="256" fill="#ffca28" />
                        <path d="M399.68 208.32c-8.832 0-16-7.168-16-16 0-17.632-14.336-32-32-32s-32 14.368-32 32c0 8.832-7.168 16-16 16s-16-7.168-16-16c0-35.296 28.704-64 64-64s64 28.704 64 64c0 8.864-7.168 16-16 16zm-192 0c-8.832 0-16-7.168-16-16 0-17.632-14.368-32-32-32s-32 14.368-32 32c0 8.832-7.168 16-16 16s-16-7.168-16-16c0-35.296 28.704-64 64-64s64 28.704 64 64c0 8.864-7.168 16-16 16z" fill="#6d4c41" />
                        <path d="M437.696 294.688c-3.04-4-7.744-6.368-12.736-6.368H86.4a15.898 15.898 0 00-12.736 6.336 15.97 15.97 0 00-2.688 14.016C94.112 390.88 170.08 448.32 255.648 448.32s161.536-57.44 184.672-139.648a15.859 15.859 0 00-2.624-13.984z" fill="#fafafa" />
                    </svg>
                </div>
                <div class="mx-2 w-7 animate-bounce animate-delay-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <circle cx="256" cy="256" r="256" fill="#ffca28" />
                        <path d="M399.68 208.32c-8.832 0-16-7.168-16-16 0-17.632-14.336-32-32-32s-32 14.368-32 32c0 8.832-7.168 16-16 16s-16-7.168-16-16c0-35.296 28.704-64 64-64s64 28.704 64 64c0 8.864-7.168 16-16 16zm-192 0c-8.832 0-16-7.168-16-16 0-17.632-14.368-32-32-32s-32 14.368-32 32c0 8.832-7.168 16-16 16s-16-7.168-16-16c0-35.296 28.704-64 64-64s64 28.704 64 64c0 8.864-7.168 16-16 16z" fill="#6d4c41" />
                        <path d="M437.696 294.688c-3.04-4-7.744-6.368-12.736-6.368H86.4a15.898 15.898 0 00-12.736 6.336 15.97 15.97 0 00-2.688 14.016C94.112 390.88 170.08 448.32 255.648 448.32s161.536-57.44 184.672-139.648a15.859 15.859 0 00-2.624-13.984z" fill="#fafafa" />
                    </svg>
                </div>
                <div class="mx-2 w-7 animate-bounce animate-delay-6">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <circle cx="256" cy="256" r="256" fill="#ffca28" />
                        <path d="M399.68 208.32c-8.832 0-16-7.168-16-16 0-17.632-14.336-32-32-32s-32 14.368-32 32c0 8.832-7.168 16-16 16s-16-7.168-16-16c0-35.296 28.704-64 64-64s64 28.704 64 64c0 8.864-7.168 16-16 16zm-192 0c-8.832 0-16-7.168-16-16 0-17.632-14.368-32-32-32s-32 14.368-32 32c0 8.832-7.168 16-16 16s-16-7.168-16-16c0-35.296 28.704-64 64-64s64 28.704 64 64c0 8.864-7.168 16-16 16z" fill="#6d4c41" />
                        <path d="M437.696 294.688c-3.04-4-7.744-6.368-12.736-6.368H86.4a15.898 15.898 0 00-12.736 6.336 15.97 15.97 0 00-2.688 14.016C94.112 390.88 170.08 448.32 255.648 448.32s161.536-57.44 184.672-139.648a15.859 15.859 0 00-2.624-13.984z" fill="#fafafa" />
                    </svg>
                </div>
            </div>
            <div class="w-72">
                <p class="text-sm text-center">We are happy to see more than a thousand people have used Roomee to find roommates. Are you next?
            </div>
        </div>

        <div class="my-16 bg-transparent testimonial-carousel">
            <x-testimonial-card class="is-selected">
                <p>
                    "I had Roomee on my homescreen and completely forgot that it was actually a website. I also met my super-fun roommate on Roomee"
                </p>

                <x-slot name="avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 145 145" style="enable-background:new  0 0 145 145;">
                        <g>
                            <ellipse style="fill:#10303F;" cx="72.5" cy="57.383" rx="30.015" ry="31.728" />
                            <g>
                                <path style="fill:#F1C9A5;" d="M109.374,115.394c-4.963-9.396-36.874-15.292-36.874-15.292s-31.911,5.896-36.874,15.292
				                    C31.957,128.433,28.888,145,28.888,145H72.5h43.612C116.112,145,114.039,127.236,109.374,115.394z" />
                                <path style="fill:#E4B692;" d="M72.5,100.102c0,0,31.911,5.896,36.874,15.292c4.665,11.842,6.738,29.606,6.738,29.606H72.5
				                    V100.102z" />
                                <rect x="63.813" y="81.001" style="fill:#F1C9A5;" width="17.374" height="29.077" />
                                <rect x="72.5" y="81.001" style="fill:#E4B692;" width="8.687" height="29.077" />
                                <path style="opacity:0.1;fill:#DDAC8C;enable-background:new    ;" d="M63.813,94.474c1.562,4.485,7.869,7.057,12.5,7.057
				                    c1.675,0,3.305-0.281,4.874-0.795V81.001H63.813V94.474z" />
                                <path style="fill:#F1C9A5;" d="M94.838,62.652c0-18.162-10.002-28.489-22.338-28.489S50.162,44.491,50.162,62.652
				                    c0,18.162,10.001,32.887,22.338,32.887S94.838,80.814,94.838,62.652z" />
                                <path style="fill:#E4B692;" d="M91.438,75.245c-4.049-0.451-6.783-5.088-6.098-10.353c0.677-5.268,4.512-9.18,8.563-8.732
				                    c4.047,0.449,6.777,5.084,6.093,10.353C99.318,71.781,95.487,75.689,91.438,75.245z" />
                                <path style="fill:#F1C9A5;" d="M45.161,66.513c-0.684-5.269,2.046-9.904,6.091-10.353c4.053-0.448,7.889,3.463,8.568,8.732
				                    c0.684,5.265-2.053,9.901-6.1,10.353C49.671,75.689,45.84,71.781,45.161,66.513z" />
                                <path style="fill:#E4B692;" d="M94.838,62.652c0-18.162-10.002-28.489-22.338-28.489v61.375
				                    C84.836,95.539,94.838,80.814,94.838,62.652z" />
                                <path style="fill:#E00315;" d="M109.374,115.394c-0.296-0.561-0.694-1.109-1.17-1.646c-5.415,7.129-19.354,12.205-35.703,12.205
				                    c-0.001,0-0.001,0-0.001,0l0,0c-0.001,0-0.001,0-0.001,0c-16.348,0-30.288-5.076-35.704-12.204
				                    c-0.476,0.535-0.874,1.083-1.169,1.644C31.957,128.433,28.888,145,28.888,145H72.5h43.612
				                    C116.112,145,114.039,127.236,109.374,115.394z" />
                            </g>
                            <g>
                                <g>
                                    <g>
                                        <path style="fill:#452317;" d="M73.599,83.247c-0.524,0-1.099,0.599-1.099,0.599s-0.574-0.599-1.099-0.599
						                    c-0.524,0-1.648,0.936-2.659,1.048c1.348,1.348,2.909,1.834,3.757,1.834s2.409-0.486,3.757-1.834
						                    C75.247,84.183,74.123,83.247,73.599,83.247z" />
                                    </g>
                                </g>
                            </g>
                            <circle style="fill:#10303F;" cx="72.5" cy="30.131" r="15.108" />
                            <path style="fill:#10303F;" d="M96.669,60.095c0-0.054,0.004-0.106,0.004-0.16c0-14.089-9.538-28.956-22.889-28.956
			                    c-13.35,0-25.456,14.867-25.456,28.956c0,0.054,0.003,0.106,0.003,0.16H96.669z" />
                            <rect x="63.813" y="97.856" style="fill:#10303F;" width="17.374" height="4.18" />
                        </g>
                    </svg>
                </x-slot>

                <x-slot name="fullname">
                    Ana Castello
                </x-slot>
                <x-slot name="username">
                    @Kolh69
                </x-slot>
            </x-testimonial-card>
            <x-testimonial-card>
                <p>
                    "I was so worried about getting a roommate because i've had bad experiences in the past, but Roomee helped me find the perfect roommate"
                </p>

                <x-slot name="avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 145 145" style="enable-background:new  0 0 145 145;">
                        <g>
                            <ellipse style="fill:#10303F;" cx="72.5" cy="57.383" rx="30.015" ry="31.728" />
                            <g>
                                <path style="fill:#F1C9A5;" d="M109.374,115.394c-4.963-9.396-36.874-15.292-36.874-15.292s-31.911,5.896-36.874,15.292
				                    C31.957,128.433,28.888,145,28.888,145H72.5h43.612C116.112,145,114.039,127.236,109.374,115.394z" />
                                <path style="fill:#E4B692;" d="M72.5,100.102c0,0,31.911,5.896,36.874,15.292c4.665,11.842,6.738,29.606,6.738,29.606H72.5
				                    V100.102z" />
                                <rect x="63.813" y="81.001" style="fill:#F1C9A5;" width="17.374" height="29.077" />
                                <rect x="72.5" y="81.001" style="fill:#E4B692;" width="8.687" height="29.077" />
                                <path style="opacity:0.1;fill:#DDAC8C;enable-background:new    ;" d="M63.813,94.474c1.562,4.485,7.869,7.057,12.5,7.057
				                    c1.675,0,3.305-0.281,4.874-0.795V81.001H63.813V94.474z" />
                                <path style="fill:#F1C9A5;" d="M94.838,62.652c0-18.162-10.002-28.489-22.338-28.489S50.162,44.491,50.162,62.652
				                    c0,18.162,10.001,32.887,22.338,32.887S94.838,80.814,94.838,62.652z" />
                                <path style="fill:#E4B692;" d="M91.438,75.245c-4.049-0.451-6.783-5.088-6.098-10.353c0.677-5.268,4.512-9.18,8.563-8.732
				                    c4.047,0.449,6.777,5.084,6.093,10.353C99.318,71.781,95.487,75.689,91.438,75.245z" />
                                <path style="fill:#F1C9A5;" d="M45.161,66.513c-0.684-5.269,2.046-9.904,6.091-10.353c4.053-0.448,7.889,3.463,8.568,8.732
				                    c0.684,5.265-2.053,9.901-6.1,10.353C49.671,75.689,45.84,71.781,45.161,66.513z" />
                                <path style="fill:#E4B692;" d="M94.838,62.652c0-18.162-10.002-28.489-22.338-28.489v61.375
				                    C84.836,95.539,94.838,80.814,94.838,62.652z" />
                                <path style="fill:#E00315;" d="M109.374,115.394c-0.296-0.561-0.694-1.109-1.17-1.646c-5.415,7.129-19.354,12.205-35.703,12.205
				                    c-0.001,0-0.001,0-0.001,0l0,0c-0.001,0-0.001,0-0.001,0c-16.348,0-30.288-5.076-35.704-12.204
				                    c-0.476,0.535-0.874,1.083-1.169,1.644C31.957,128.433,28.888,145,28.888,145H72.5h43.612
				                    C116.112,145,114.039,127.236,109.374,115.394z" />
                            </g>
                            <g>
                                <g>
                                    <g>
                                        <path style="fill:#452317;" d="M73.599,83.247c-0.524,0-1.099,0.599-1.099,0.599s-0.574-0.599-1.099-0.599
						                    c-0.524,0-1.648,0.936-2.659,1.048c1.348,1.348,2.909,1.834,3.757,1.834s2.409-0.486,3.757-1.834
						                    C75.247,84.183,74.123,83.247,73.599,83.247z" />
                                    </g>
                                </g>
                            </g>
                            <circle style="fill:#10303F;" cx="72.5" cy="30.131" r="15.108" />
                            <path style="fill:#10303F;" d="M96.669,60.095c0-0.054,0.004-0.106,0.004-0.16c0-14.089-9.538-28.956-22.889-28.956
			                    c-13.35,0-25.456,14.867-25.456,28.956c0,0.054,0.003,0.106,0.003,0.16H96.669z" />
                            <rect x="63.813" y="97.856" style="fill:#10303F;" width="17.374" height="4.18" />
                        </g>
                    </svg>
                </x-slot>

                <x-slot name="fullname">
                    Ana Castello
                </x-slot>
                <x-slot name="username">
                    @Kolh69
                </x-slot>
            </x-testimonial-card>
            <x-testimonial-card>
                <p>
                    "Roomee is a lifesaver. I had a limited budget and school resumption was a week away. Roomee helped me get a roommate before resumption"
                </p>

                <x-slot name="avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 145 145" style="enable-background:new  0 0 145 145;">
                        <g>
                            <ellipse style="fill:#10303F;" cx="72.5" cy="57.383" rx="30.015" ry="31.728" />
                            <g>
                                <path style="fill:#F1C9A5;" d="M109.374,115.394c-4.963-9.396-36.874-15.292-36.874-15.292s-31.911,5.896-36.874,15.292
				                    C31.957,128.433,28.888,145,28.888,145H72.5h43.612C116.112,145,114.039,127.236,109.374,115.394z" />
                                <path style="fill:#E4B692;" d="M72.5,100.102c0,0,31.911,5.896,36.874,15.292c4.665,11.842,6.738,29.606,6.738,29.606H72.5
				                    V100.102z" />
                                <rect x="63.813" y="81.001" style="fill:#F1C9A5;" width="17.374" height="29.077" />
                                <rect x="72.5" y="81.001" style="fill:#E4B692;" width="8.687" height="29.077" />
                                <path style="opacity:0.1;fill:#DDAC8C;enable-background:new    ;" d="M63.813,94.474c1.562,4.485,7.869,7.057,12.5,7.057
				                    c1.675,0,3.305-0.281,4.874-0.795V81.001H63.813V94.474z" />
                                <path style="fill:#F1C9A5;" d="M94.838,62.652c0-18.162-10.002-28.489-22.338-28.489S50.162,44.491,50.162,62.652
				                    c0,18.162,10.001,32.887,22.338,32.887S94.838,80.814,94.838,62.652z" />
                                <path style="fill:#E4B692;" d="M91.438,75.245c-4.049-0.451-6.783-5.088-6.098-10.353c0.677-5.268,4.512-9.18,8.563-8.732
				                    c4.047,0.449,6.777,5.084,6.093,10.353C99.318,71.781,95.487,75.689,91.438,75.245z" />
                                <path style="fill:#F1C9A5;" d="M45.161,66.513c-0.684-5.269,2.046-9.904,6.091-10.353c4.053-0.448,7.889,3.463,8.568,8.732
				                    c0.684,5.265-2.053,9.901-6.1,10.353C49.671,75.689,45.84,71.781,45.161,66.513z" />
                                <path style="fill:#E4B692;" d="M94.838,62.652c0-18.162-10.002-28.489-22.338-28.489v61.375
				                    C84.836,95.539,94.838,80.814,94.838,62.652z" />
                                <path style="fill:#E00315;" d="M109.374,115.394c-0.296-0.561-0.694-1.109-1.17-1.646c-5.415,7.129-19.354,12.205-35.703,12.205
				                    c-0.001,0-0.001,0-0.001,0l0,0c-0.001,0-0.001,0-0.001,0c-16.348,0-30.288-5.076-35.704-12.204
				                    c-0.476,0.535-0.874,1.083-1.169,1.644C31.957,128.433,28.888,145,28.888,145H72.5h43.612
				                    C116.112,145,114.039,127.236,109.374,115.394z" />
                            </g>
                            <g>
                                <g>
                                    <g>
                                        <path style="fill:#452317;" d="M73.599,83.247c-0.524,0-1.099,0.599-1.099,0.599s-0.574-0.599-1.099-0.599
						                    c-0.524,0-1.648,0.936-2.659,1.048c1.348,1.348,2.909,1.834,3.757,1.834s2.409-0.486,3.757-1.834
						                    C75.247,84.183,74.123,83.247,73.599,83.247z" />
                                    </g>
                                </g>
                            </g>
                            <circle style="fill:#10303F;" cx="72.5" cy="30.131" r="15.108" />
                            <path style="fill:#10303F;" d="M96.669,60.095c0-0.054,0.004-0.106,0.004-0.16c0-14.089-9.538-28.956-22.889-28.956
			                    c-13.35,0-25.456,14.867-25.456,28.956c0,0.054,0.003,0.106,0.003,0.16H96.669z" />
                            <rect x="63.813" y="97.856" style="fill:#10303F;" width="17.374" height="4.18" />
                        </g>
                    </svg>
                </x-slot>

                <x-slot name="fullname">
                    Ana Castello
                </x-slot>
                <x-slot name="username">
                    @Kolh69
                </x-slot>
            </x-testimonial-card>
            <x-testimonial-card>
                <p>
                    "I never have to go through the stress of
                    posting ads all over social media for a roommate. Roomee makes it so easy to find the best roommate"
                </p>

                <x-slot name="avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 145 145" style="enable-background:new  0 0 145 145;">
                        <g>
                            <ellipse style="fill:#10303F;" cx="72.5" cy="57.383" rx="30.015" ry="31.728" />
                            <g>
                                <path style="fill:#F1C9A5;" d="M109.374,115.394c-4.963-9.396-36.874-15.292-36.874-15.292s-31.911,5.896-36.874,15.292
				                    C31.957,128.433,28.888,145,28.888,145H72.5h43.612C116.112,145,114.039,127.236,109.374,115.394z" />
                                <path style="fill:#E4B692;" d="M72.5,100.102c0,0,31.911,5.896,36.874,15.292c4.665,11.842,6.738,29.606,6.738,29.606H72.5
				                    V100.102z" />
                                <rect x="63.813" y="81.001" style="fill:#F1C9A5;" width="17.374" height="29.077" />
                                <rect x="72.5" y="81.001" style="fill:#E4B692;" width="8.687" height="29.077" />
                                <path style="opacity:0.1;fill:#DDAC8C;enable-background:new    ;" d="M63.813,94.474c1.562,4.485,7.869,7.057,12.5,7.057
				                    c1.675,0,3.305-0.281,4.874-0.795V81.001H63.813V94.474z" />
                                <path style="fill:#F1C9A5;" d="M94.838,62.652c0-18.162-10.002-28.489-22.338-28.489S50.162,44.491,50.162,62.652
				                    c0,18.162,10.001,32.887,22.338,32.887S94.838,80.814,94.838,62.652z" />
                                <path style="fill:#E4B692;" d="M91.438,75.245c-4.049-0.451-6.783-5.088-6.098-10.353c0.677-5.268,4.512-9.18,8.563-8.732
				                    c4.047,0.449,6.777,5.084,6.093,10.353C99.318,71.781,95.487,75.689,91.438,75.245z" />
                                <path style="fill:#F1C9A5;" d="M45.161,66.513c-0.684-5.269,2.046-9.904,6.091-10.353c4.053-0.448,7.889,3.463,8.568,8.732
				                    c0.684,5.265-2.053,9.901-6.1,10.353C49.671,75.689,45.84,71.781,45.161,66.513z" />
                                <path style="fill:#E4B692;" d="M94.838,62.652c0-18.162-10.002-28.489-22.338-28.489v61.375
				                    C84.836,95.539,94.838,80.814,94.838,62.652z" />
                                <path style="fill:#E00315;" d="M109.374,115.394c-0.296-0.561-0.694-1.109-1.17-1.646c-5.415,7.129-19.354,12.205-35.703,12.205
				                    c-0.001,0-0.001,0-0.001,0l0,0c-0.001,0-0.001,0-0.001,0c-16.348,0-30.288-5.076-35.704-12.204
				                    c-0.476,0.535-0.874,1.083-1.169,1.644C31.957,128.433,28.888,145,28.888,145H72.5h43.612
				                    C116.112,145,114.039,127.236,109.374,115.394z" />
                            </g>
                            <g>
                                <g>
                                    <g>
                                        <path style="fill:#452317;" d="M73.599,83.247c-0.524,0-1.099,0.599-1.099,0.599s-0.574-0.599-1.099-0.599
						                    c-0.524,0-1.648,0.936-2.659,1.048c1.348,1.348,2.909,1.834,3.757,1.834s2.409-0.486,3.757-1.834
						                    C75.247,84.183,74.123,83.247,73.599,83.247z" />
                                    </g>
                                </g>
                            </g>
                            <circle style="fill:#10303F;" cx="72.5" cy="30.131" r="15.108" />
                            <path style="fill:#10303F;" d="M96.669,60.095c0-0.054,0.004-0.106,0.004-0.16c0-14.089-9.538-28.956-22.889-28.956
			                    c-13.35,0-25.456,14.867-25.456,28.956c0,0.054,0.003,0.106,0.003,0.16H96.669z" />
                            <rect x="63.813" y="97.856" style="fill:#10303F;" width="17.374" height="4.18" />
                        </g>
                    </svg>
                </x-slot>

                <x-slot name="fullname">
                    Ana Castello
                </x-slot>
                <x-slot name="username">
                    @Kolh69
                </x-slot>
            </x-testimonial-card>
        </div>
    </section>
    <!-- End of Carousel Section -->

    <!-- PWA section -->
    <div class="relative z-20 w-full bg-gray-900 border-b border-gray-700 hero hero2-bg">
        <div class="absolute z-0 w-full h-full blur-bg"></div>
        <div class="relative z-20 flex flex-col items-center justify-center w-4/5 pt-20 pb-12 mx-auto md:flex-row md:py-24">
            <div class="max-w-sm mb-8 w-60 sm:w-1/2 md:w-1/3 lg:w-1/4 md:mb-0">
                <img src="{{ asset('images/pwa.png') }}" alt="image of roomee's pwa prompt" />
            </div>
            <div class="flex flex-col items-center justify-center w-full max-w-sm pt-2 pb-12 font-bold text-center text-gray-50 bg-gradient-to-t from-gray-900-400 via-blue-400 to-transparent md:py-0 sm:text-xl sm:text-justify md:px-4 md:mt-0 md:ml-8 md:w-80 lg:w-1/2">
                <p class="mb-10">Roomee is a PWA (progressive web app) that can be installed to your homescreen through your browser. This way you get instant access, notifications, faster browsing with an app-like performance and lots more. it also takes considerably less storage space. </p>
                <div class="w-full text-center md:text-left">
                    <x-ctaButton href=" {{ route('features') }}" class="px-10 py-4 text-lg font-semibold leading-3 text-gray-100 bg-blue-500 md:text-xl hover:bg-blue-600">Learn More</x-ctaButton>
                </div>
            </div>
        </div>
    </div>
    <!-- End of PWA Section -->

</x-landing-layout>