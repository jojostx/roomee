<x-filament::page>
    <form wire:submit.prevent="send" class="space-y-6">
        {{ $this->form }}

        <x-filament::button type="submit">
            Send Message
        </x-filament::button>
    </form>
</x-filament::page>
