<div class="w-full h-full mx-auto bg-secondary-50 max-w-7xl">
    <x-page-header>
        <h1 class="text-sm font-bold uppercase text-secondary-500">
            Dashboard
        </h1>
        <h1 class="font-bold">
            Hello, {{ auth()->user()->first_name }}
        </h1>
    </x-page-header>

    <div class="px-3 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        {{ $this->table }}
    </div>
</div>