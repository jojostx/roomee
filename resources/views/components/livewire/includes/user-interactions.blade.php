@props(['user'])
@php
    $classes = "flex items-center gap-2 ";
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
  <x-livewire.includes.favoriting-sxn :user="$user" />
  <x-livewire.includes.requesting-sxn :user="$user" />
  <x-filament-support::icon-button 
    size="sm"
    color="secondary"
    icon="heroicon-s-dots-horizontal" 
    x-data="" 
    x-on:click="$dispatch('open-modal', 
      { 
        id: 'user-interaction-menu', 
        user_id: '{{ $user->uuid }}', 
        user_name: '{{ $user->full_name }}',
        user_is_blocked: {{ auth()->user()->hasBlocked($user) ? 'true' : 'false' }},
      }
    )"
    style="border-radius: 0.5rem;"
    class="border rounded-lg border-secondary-300 disabled:cursor-not-allowed disabled:pointer-events-none shrink-0 " 
    aria-label="show user menu" 
    title="show user menu"
  />
</div>