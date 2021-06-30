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

    </div>
</x-app-layout>