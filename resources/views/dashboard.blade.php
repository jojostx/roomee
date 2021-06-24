<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="grid gap-4 lg:gap-6 grid-col-1 md:grid-cols-2 lg:grid-cols-3">
     

        @livewire('issues-modal')

        @foreach ($users as $user)
        @livewire('user-card', ['user' => $user], key($user->id))
        @endforeach

        @prepend('scripts')
        <script>
            // Livewire.on('blockOrReport', (...params) => {
            //     let username = document.querySelectorAll('.user-name');
            //     let [id, firstname] = params;

            //     username.forEach((obj) => {
            //         obj.innerText = `${firstname}`;
            //     })
            // })

            // Emit 'id' from 'ping listener',  then later emit an action 'block||report'
            // if it is report then an array of issues should be included 
            // in the parameters wen emitting the event
        </script>
        @endprepend
    </div>
</x-app-layout>