<div class="w-full h-screen mx-auto bg-gray-100 max-w-7xl">
    <x-page-header>Dashboard</x-page-header>

    <div class="flex flex-wrap px-3 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        @forelse ($this->users as $user)
        <div class="grid w-full gap-4 lg:gap-6 grid-col-1 sm:grid-cols-2 md:grid-cols-3">
            @livewire('components.cards.dashboard-card', ['user' => $user], key($user->id))
        </div>
        @empty
        <div class="flex items-center justify-center w-full my-2">
            <div class="p-3 my-4 bg-white border rounded-md shadow">
                There are currently no users that matches your preferences.
            </div>
        </div>
        @endforelse
    </div>
</div>