<x-filament::button
    x-data
    x-on:click="$dispatch('close-update-form', { id : '{{ $getId() }}' });" 
    size='sm'
    type='button'
    color='secondary'
    style='font-weight: 600;'
>
    {{ __('Cancel') }}
</x-filament::button>
