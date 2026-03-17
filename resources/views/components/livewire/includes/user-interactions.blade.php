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

  <x-filament::icon-button
    size="sm"
    color="warning"
    icon="heroicon-o-flag"
    wire:click="mountAction('reportUser', { user: '{{ $user->uuid }}' })"
    style="border-radius: 0.5rem;"
    class="border rounded-lg border-secondary-300 disabled:cursor-not-allowed disabled:pointer-events-none shrink-0"
    aria-label="report user"
    title="Report {{ $user->full_name }}"
  />

  <x-filament::icon-button
    size="sm"
    color="danger"
    icon="heroicon-o-no-symbol"
    wire:click="mountAction('blockUser', { user: '{{ $user->uuid }}' })"
    style="border-radius: 0.5rem;"
    class="border rounded-lg border-secondary-300 disabled:cursor-not-allowed disabled:pointer-events-none shrink-0"
    aria-label="{{ $user_is_blocked_by_auth ? 'unblock' : 'block' }} user"
    title="{{ $user_is_blocked_by_auth ? 'Unblock' : 'Block' }} {{ $user->full_name }}"
  />
</div>
