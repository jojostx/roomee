@props(['user'])
@php
    $classes = "flex items-center gap-4 ";
    $auth_is_blocked_by_user = $user->hasBlocked(auth()->user());
    $user_is_blocked_by_auth = $user->isBlockedBy(auth()->user());
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
  @unless ($auth_is_blocked_by_user)
  <x-livewire.includes.requesting-sxn :user="$user" />
  @endunless

  <x-livewire.includes.favoriting-sxn :user="$user" />

  <x-filament-support::icon-button 
    size="sm"
    color="secondary"
    icon="heroicon-s-dots-vertical" 
    x-data="" 
    x-on:click="$dispatch('open-modal', 
      { 
        id: 'user-interaction-menu', 
        user_id: '{{ $user->uuid }}', 
        user_name: '{{ $user->full_name }}',
        user_is_blocked: {{ $user_is_blocked_by_auth ? 'true' : 'false' }},
      }
    )"
    style="border-radius: 0.5rem;"
    class="border rounded-lg border-secondary-300 disabled:cursor-not-allowed disabled:pointer-events-none shrink-0 " 
    aria-label="show user menu" 
    title="show user menu"
  />
</div>