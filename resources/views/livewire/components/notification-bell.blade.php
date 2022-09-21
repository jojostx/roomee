<x-dropdown align="right" width="64" class="ml-auto mr-2">
    <x-slot name="trigger">
        <button class="relative text-gray-500 outline-none hover:text-gray-600 focus:text-primary-600 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-6" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            @if ($this->unseenNotifs)
            <div class="absolute top-0 right-0 w-3 h-3 bg-primary-500 border-2 border-white rounded-full"></div>
            @endif
        </button>
    </x-slot>

    <x-slot name="content">
        @if (!$this->unseenNotifs)
        <div class="p-2 px-3 text-sm text-gray-600">
            <p>You have no new notifications</p>
            <a href="{{ route('notifications') }}" class="text-primary-600 hover:text-primary-800">view all</a>
        </div>
        @else
        <div class="flex items-center justify-between p-2 px-3 text-sm text-gray-600">
            <p>You have
                <span class="font-semibold text-gray-900">
                    {{ $this->unseenNotifs }}
                </span>
                new
                {{ Str::plural('notification', $this->unseenNotifs) }}
            </p>
            <a href="{{ route('notifications') }}" class="text-primary-600 hover:text-primary-800">view all</a>
        </div>
        @endif
    </x-slot>
</x-dropdown>