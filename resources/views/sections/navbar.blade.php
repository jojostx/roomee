<nav x-data="{ open: false }" class="flex flex-row items-center justify-between w-11/12 h-full">

    <!-- Application Logo -->
    <div class="flex items-center flex-shrink-0">
        <a href="{{ route('home') }}">
            <x-application-logo class="block w-auto h-10 text-gray-200 fill-current" />
        </a>
    </div>

    <!-- Primary Navigation Menu -->
    <div class="items-center hidden sm:flex">
        <div class="flex flex-shrink-0">
            <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-nav-link>
            <x-nav-link :href="route('features')" :active="request()->routeIs('features')">
                {{ __('Features') }}
            </x-nav-link>
            <x-nav-link :href="route('faqs')" :active="request()->routeIs('faqs')">
                {{ __('FAQs') }}
            </x-nav-link>
            <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
                {{ __('About Us') }}
            </x-nav-link>
        </div>
        <div class="relative flex items-center justify-center min-h-full ml-2 sm:items-center sm:pt-0">
            @if (Route::has('login'))
            <div class="py-2 sm:block">
                @auth
                <a href="{{ url('/dashboard') }}" class="px-3 py-1 text-xs font-semibold text-gray-200 border-2 border-white rounded-full hover:bg-white hover:text-gray-900 focus:bg-white focus:text-gray-900 ">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="px-3 py-1 text-xs font-semibold text-gray-200 border-2 border-white rounded-full hover:bg-white hover:text-gray-900 focus:bg-white focus:text-gray-900 ">SIGN IN</a>

                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="px-3 py-1 ml-2 text-xs font-semibold text-gray-200 border-2 border-white rounded-full hover:bg-white hover:text-gray-900 focus:bg-white focus:text-gray-900">SIGN UP</a>
                @endif
                @endauth
            </div>
            @endif
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div class="relative z-40 block sm:hidden">
        <div class="flex items-center sm:hidden">
            <button @click="open = !open" class="inline-flex items-center justify-center p-2 text-gray-200 transition duration-150 ease-in-out rounded-md hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div x-show="open" @click.away="open = false" class="absolute right-0 flex flex-col items-center justify-between w-48 pb-4 overflow-hidden bg-gray-900 border border-gray-700 rounded-md shadow-lg top-14">
            <div class="flex flex-col justify-between flex-shrink-0 w-full">
                <x-dark-dropdown-link :href="route('home')" :active="request()->routeIs('home')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-4">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('Home') }}
                </x-dark-dropdown-link>
                <x-dark-dropdown-link :href="route('features')" :active="request()->routeIs('features')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-4">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    {{ __('Features') }}
                </x-dark-dropdown-link>
                <x-dark-dropdown-link :href="route('faqs')" :active="request()->routeIs('faqs')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-4">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('FAQs') }}
                </x-dark-dropdown-link>
                <x-dark-dropdown-link :href="route('about')" :active="request()->routeIs('about')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-4">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('About Us') }}
                </x-dark-dropdown-link>
                <x-dark-dropdown-link :href="route('contact')" :active="request()->routeIs('contact')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-4">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    {{ __('Contact Us') }}
                </x-dark-dropdown-link>
            </div>
            <div class="relative flex items-center justify-center w-full px-4 pt-4 border-t border-gray-700 sm:items-center sm:pt-0 ">
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/dashboard') }}" class="px-3 py-1 text-xs font-semibold text-gray-200 border-2 border-white rounded-full hover:bg-white hover:text-gray-900 focus:bg-white focus:text-gray-900 ">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="flex-1 px-2 py-1 text-xs font-semibold text-center text-gray-200 border-2 border-white rounded-full hover:bg-white hover:text-gray-900">SIGN IN</a>

                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="flex-1 px-2 py-1 ml-2 text-xs font-semibold text-center text-gray-200 border-2 border-white rounded-full hover:bg-white hover:text-gray-900">SIGN UP</a>
                @endif
                @endauth
                @endif
            </div>
        </div>
    </div>
</nav>