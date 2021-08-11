<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div>
        @livewire('issues-modal')

        @livewire('dashboard')

        <x-livewire.toast-notif></x-livewire.toast-notif>

        <script>
            // window.addEventListener('DOMContentLoaded', (e) => {
            //     // console.log('ok');
            //     Echo.channel(`request.{{ auth()->user()->id }}`)
            //         .listen('RoommateRequestUpdated', (e) => {
            //             console.log(e);
            //         });
            // })
        </script>
    </div>
</x-app-layout>