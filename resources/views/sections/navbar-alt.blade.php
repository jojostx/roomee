<nav class="flex flex-row items-center justify-between w-11/12 h-full py-4">

    <!-- Application Logo -->
    <div class="flex items-center flex-shrink-0">
        <a href="{{ route('home') }}" aria-label="application logo" title="application logo">
            <x-livewire.logo-alt class="block text-gray-200 fill-current w-36" style="max-width: 120px;"/>
        </a>
    </div>

    <a href="{{ route('dashboard')}}" style="border-width: 1.5px;" class="flex items-center justify-start px-2 py-1 text-sm text-blue-600 transition duration-150 ease-in-out border border-blue-700 rounded-md sm:text-lg hover:text-blue-700 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 focus:text-blue-700">Dashboard</a>

</nav>