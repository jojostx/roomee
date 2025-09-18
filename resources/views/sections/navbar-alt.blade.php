<div class="w-full px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
        <!-- Logo -->
        <div class="flex items-center flex-shrink-0">
            <a href="{{ route('home') }}" aria-label="application logo" title="application logo">
                <x-application-logo class="block w-auto h-10 text-secondary-600 fill-current" />
            </a>
        </div>

        <!-- Settings Dropdown -->
        <div class="flex items-center">
            @unless (request()->routeIs('dashboard'))
            <a href="{{ route('dashboard') }}" style="border-width: 1.5px;" class="items-center justify-start hidden px-2 py-1 text-sm transition duration-150 ease-in-out border rounded-md text-primary-700 border-primary-700 md:flex sm:text-base hover:text-primary-700 hover:bg-primary-200 focus:outline-none focus:bg-primary-200 focus:text-primary-700">Dashboard</a>
            @endunless

            <div class="mx-2">
                @livewire('notifications')
            </div>

            <x-nav-menu />
        </div>
    </div>
</div>