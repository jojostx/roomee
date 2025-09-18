@props([
    'darkMode' => false,
])

<div aria-hidden="true" {{ $attributes->class([
    'border-t filament-hr',
    'dark:border-secondary-700' => $darkMode,
]) }}></div>
