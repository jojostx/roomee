<div class="w-full px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
        <!-- Logo -->
        <div class="flex items-center flex-shrink-0">
            <a href="{{ route('home') }}" aria-label="application logo" title="application logo">
                <x-application-logo class="block w-auto h-10 text-gray-600 fill-current" />
            </a>
        </div>

        <!-- Settings Dropdown -->
        <div class="flex items-center ml-6">
            <a href="{{ route('dashboard')}}" style="border-width: 1.5px;" class="items-center justify-start hidden px-2 py-1 mr-4 text-sm text-primary-700 transition duration-150 ease-in-out border border-primary-700 rounded-md md:flex sm:text-base hover:text-primary-700 hover:bg-primary-200 focus:outline-none focus:bg-primary-200 focus:text-primary-700">Dashboard</a>
            <x-dropdown align="right" width="56">
                <x-slot name="trigger">
                    <button aria-label="settings dropdown button" title="settings dropdown button" class="flex items-center text-sm font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                        <div class="mr-2 overflow-hidden rounded-full lg:mr-4 w-9 h-9 lg:w-12 lg:h-12">
                            @if (auth()->user()->avatar)
                            <img id="avatar" src="{{ auth()->user()->avatarPath }}" alt="avatar image" width="100%" height="100%">
                            @else
                            <svg id="pl-ava" class="w-full h-full text-white bg-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            @endif
                        </div>
                        <div>{{ Auth::user()->firstname }}</div>
                        <div class="ml-1">
                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('dashboard')">
                            {{ __('Dashboard') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.view', ['user' => auth()->user() ])" :active="request()->fullUrl() == route('profile.view', [ 'user'=> auth()->user() ])">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('requests')" :active="request()->routeIs('requests')">
                            {{ __('Requests') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('favorites')" :active="request()->routeIs('favorites')">
                            {{ __('Favorites') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('blocklist')" :active="request()->routeIs('blocklist')">
                            {{ __('Blocklist') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            {{ __('Logout') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</div>