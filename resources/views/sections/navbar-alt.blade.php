<nav class="flex flex-row items-center justify-between w-11/12 h-full">

    <!-- Application Logo -->
    <div class="flex items-center flex-shrink-0">
        <a href="{{ route('home') }}" aria-label="application logo" title="application logo">
            <x-livewire.logo-alt class="block text-gray-200 fill-current w-36" style="max-width: 120px;"/>
        </a>
    </div>

    <a href="{{ route('dashboard')}}" class="px-4 py-1 border border-gray-500 rounded-md hover:shadow hover:border-blue-700 hover:text-blue-800 focus:border-blue-600 focus:text-blue-700">Dashboard</a>

</nav>