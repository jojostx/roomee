<div class="w-full h-screen mx-auto bg-gray-100 max-w-7xl">
    <div class="px-4 py-3 mx-auto mb-2 bg-white shadow sm:py-4 md:py-6 max-w-7xl sm:px-6 lg:px-8">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Blocklist
        </h2>
    </div>

    <div class="flex flex-wrap px-3 mx-auto max-w-7xl sm:px-6 lg:px-8">
        @forelse ($this->blockedUsers as $user)
            @livewire('cards.blocklist.card', ['user' => $user], key($user->id))
        @empty
        <div class="flex items-center justify-center w-full my-2">
            <div class="p-3 my-4 bg-white border rounded-md shadow">
                You have not blocked any user.
            </div>
        </div>
        @endforelse
    </div>
    <x-livewire.toast-notif></x-livewire.toast-notif>
</div>