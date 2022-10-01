@props([
    'darkMode' => false,
])

<h3 {{ $attributes->class(['text-secondary-500 filament-modal-subheading']) }}>
    {{ $slot }}
</h3>
