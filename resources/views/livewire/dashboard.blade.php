<div class="grid gap-4 lg:gap-6 grid-col-1 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($users as $user)
                @livewire('cards.dashboard.card', ['user' => $user], key($user->id))
        @empty
        <div class="flex items-center justify-center my-2 col-span-full">
                <div class="p-3 my-4 bg-white border rounded-md shadow">
                        Your have not added any favorites.
                </div>
        </div>
        @endforelse
</div>