<nav class="flex flex-row items-center justify-between w-11/12 h-full">
    <!-- Application Logo -->
    <div class="flex items-center flex-shrink-0">
        <a href="{{ route('home') }}">
            <x-application-logo class="block h-12 fill-current text-secondary-200" />
        </a>
    </div>

    <!-- Primary Navigation Menu -->
    <div class="items-center hidden sm:flex">
        <div class="flex flex-shrink-0">
            <x-nav-link-primary :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-nav-link-primary>
            <x-nav-link-primary :href="route('features')" :active="request()->routeIs('features')">
                {{ __('Features') }}
            </x-nav-link-primary>
            <x-nav-link-primary :href="route('faqs')" :active="request()->routeIs('faqs')">
                {{ __('FAQs') }}
            </x-nav-link-primary>
            <x-nav-link-primary :href="route('about')" :active="request()->routeIs('about')">
                {{ __('About Us') }}
            </x-nav-link-primary>
        </div>
        <div class="relative flex items-center justify-center min-h-full ml-2 sm:items-center sm:pt-0">
            @if (Route::has('login'))
            <div class="py-2 sm:block">
                @auth
                @if (Auth::user()->profile_updated)
                <a href="{{ route('dashboard') }}" class="px-3 py-1 text-sm font-semibold border border-white rounded-full text-secondary-200 hover:bg-white hover:text-secondary-900 focus:bg-white focus:text-secondary-900 ">Dashboard</a>
                @else
                <a href="{{ route('profile.update') }}" class="px-3 py-1 text-sm font-semibold border border-white rounded-full text-secondary-200 hover:bg-white hover:text-secondary-900 focus:bg-white focus:text-secondary-900 ">Profile</a>
                @endif
                @endauth

                @guest
                <a href="{{ route('register') }}" class="px-3 py-1 text-sm font-semibold border border-white rounded-full text-secondary-200 hover:bg-white hover:text-secondary-900 focus:bg-white focus:text-secondary-900 ">SIGN UP</a>
                <a href="{{ route('login') }}" class="px-3 py-1 ml-2 text-sm font-semibold border border-white rounded-full text-secondary-200 hover:bg-white hover:text-secondary-900 focus:bg-white focus:text-secondary-900">SIGN IN</a>
                @endguest
            </div>
            @endif
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div class="relative z-40 block sm:hidden" x-data="{ isOpen: false }" x-trap.noscroll="isOpen" x-on:close-modal.window="if ($event.detail.id === 'nav-menu-panel') isOpen = false" x-on:open-modal.window="if ($event.detail.id === 'nav-menu-panel') isOpen = true" role="dialog" aria-modal="true">
        <div class="flex items-center sm:hidden">
            <button @click="$dispatch('open-modal', { id: 'nav-menu-panel' });" title="open menu" class="inline-flex items-center justify-center w-10 h-10 p-1 transition duration-150 ease-in-out rounded-md text-secondary-200 hover:text-secondary-900 hover:bg-secondary-100 focus:outline-none focus:bg-secondary-100 focus:text-secondary-900">
                <x-heroicon-o-menu />
            </button>
        </div>

        <div x-show="isOpen" x-cloak x-transition:enter="ease duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 flex items-center min-h-screen px-4 overflow-x-hidden overflow-y-auto transition">
            <div x-on:click="$dispatch('close-modal', { id: 'nav-menu-panel' })" aria-hidden="true" class="fixed inset-0 w-full h-full cursor-pointer filament-modal-close-overlay bg-black/50"></div>

            <div x-show="isOpen" x-on:keydown.window.escape="$dispatch('close-modal', { id: 'nav-menu-panel' })" x-transition:enter="ease duration-300" x-transition:leave="ease duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="relative w-full cursor-pointer pointer-events-none" slideover="slideover">
                <div class="relative w-full h-screen max-w-sm ml-auto -mr-4 overflow-y-auto bg-white cursor-default pointer-events-auto filament-modal-window rtl:mr-auto rtl:-ml-4">
                    <button x-on:click="isOpen = false" tabindex="-1" type="button" class="absolute cursor-pointer top-4 right-4 rtl:right-0 rtl:left-4 text-secondary-800">
                        <x-heroicon-o-x title="close menu" tabindex="-1" class="w-9 h-9"/>
                        <span class="sr-only">
                            Close
                        </span>
                    </button>

                    <div class="flex flex-col justify-center h-full space-y-2">
                        <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" icon_before="heroicon-o-home" >
                            {{ __('Home') }}
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('features')" :active="request()->routeIs('features')" icon_before="heroicon-o-lightning-bolt">
                            {{ __('Features') }}
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('faqs')" :active="request()->routeIs('faqs')" icon_before="heroicon-o-question-mark-circle">
                            {{ __('FAQs') }}
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')" icon_before="heroicon-o-user-group">
                            {{ __('About Us') }}
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')" icon_before="heroicon-o-phone">
                            {{ __('Contact Us') }}
                        </x-responsive-nav-link>

                        <div class="flex items-center border divide-x divide-secondary-400 border-secondary-400">
                            @auth
                                @if (Auth::user()->profile_updated)
                                <a href="{{ route('dashboard') }}" class="flex-1 p-4 font-semibold text-center text-secondary-700 bg-secondary-200 hover:bg-secondary-300 hover:text-primary-900 focus:text-primary-600 ">DASHBOARD</a>
                                @else
                                <a href="{{ route('profile.update') }}" class="flex-1 p-4 font-semibold text-center text-secondary-700 bg-secondary-200 hover:bg-secondary-300 hover:text-primary-900 focus:text-primary-600 ">PROFILE</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                    @csrf
                                    <a onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full p-4 font-semibold text-center cursor-pointer text-secondary-700 bg-secondary-200 hover:bg-secondary-300 hover:text-primary-900 focus:text-primary-600">LOGOUT</a>
                                </form>
                            @endauth
        
                            @guest
                                <a href="{{ route('login') }}" class="flex-1 p-4 font-semibold text-center text-secondary-700 bg-secondary-200 hover:bg-secondary-300 hover:text-primary-900 focus:text-primary-600 ">SIGN IN</a>
                                <a href="{{ route('register') }}" class="flex-1 p-4 font-semibold text-center text-secondary-700 bg-secondary-200 hover:bg-secondary-300 hover:text-primary-900 focus:text-primary-600 ">SIGN UP</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>