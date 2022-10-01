<div class="w-full h-screen mx-auto bg-secondary-100 max-w-7xl">
    <x-page-header>Favorites</x-page-header>

    <div class="flex flex-wrap px-3 mx-auto max-w-7xl sm:px-6 lg:px-8">   
    @forelse ($this->favoritedUsers as $user)
        @livewire('components.cards.favorite-card', ['user' => $user], key($user->id))
    @empty
    <div class="flex items-center justify-center w-full my-2">
        <div class="p-3 my-4 bg-white border rounded-md shadow">
            Your have not added any favorites.
        </div>
    </div>
    @endforelse
    </div>
</div>