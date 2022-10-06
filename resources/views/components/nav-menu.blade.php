<div x-data="{ isOpen: false }" x-trap.noscroll="isOpen" x-on:close-modal.window="if ($event.detail.id === 'nav-menu-panel') isOpen = false" x-on:open-modal.window="if ($event.detail.id === 'nav-menu-panel') isOpen = true" role="dialog" aria-modal="true" class="inline-block filament-modal">
  <button @click="$dispatch('open-modal', { id: 'nav-menu-panel' });" title="open menu" class="flex items-center justify-between">
    <div class="mr-2 overflow-hidden border-2 rounded-full border-primary-500 lg:mr-2 w-9 h-9">
      @if (auth()->user()->avatar)
      <img id="avatar" src="{{ auth()->user()->avatarPath }}" alt="avatar image" class="w-full">
      @else
      <svg id="pl-ava" class="w-full h-full text-white bg-secondary-300" fill="currentColor" viewBox="0 0 24 24">
        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
      </svg>
      @endif
    </div>

    <div class="w-6 h-6">
      <x-heroicon-o-menu />
    </div>
  </button>

  <div x-show="isOpen" x-cloak x-transition:enter="ease duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 flex items-center min-h-screen px-4 overflow-x-hidden overflow-y-auto transition">
    <div x-on:click="$dispatch('close-modal', { id: 'nav-menu-panel' })" aria-hidden="true" class="fixed inset-0 w-full h-full cursor-pointer filament-modal-close-overlay bg-black/50"></div>

    <div x-show="isOpen" x-on:keydown.window.escape="$dispatch('close-modal', { id: 'nav-menu-panel' })" x-transition:enter="ease duration-300" x-transition:leave="ease duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="relative w-full cursor-pointer pointer-events-none" slideover="slideover">
      <div class="relative w-full h-screen max-w-sm ml-auto -mr-4 overflow-y-auto bg-white cursor-default pointer-events-auto filament-modal-window rtl:mr-auto rtl:-ml-4">
        <button tabindex="-1" type="button" class="absolute top-4 right-4 rtl:right-0 rtl:left-4">
          <svg title="__('filament-support::components/modal.actions.close.label')" x-on:click="isOpen = false" tabindex="-1" class="w-6 h-6 cursor-pointer text-secondary-800 filament-modal-close-button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
          <span class="sr-only">
            Close
          </span>
        </button>

        <div class="flex flex-col justify-center h-full space-y-2">
          <div>
            <x-responsive-nav-link :href="route('profile.view', ['user' => auth()->user() ])" :active="request()->fullUrl() == route('profile.view', [ 'user'=> auth()->user() ])" icon_before="heroicon-o-user" >
                {{ __('Profile') }}
            </x-responsive-nav-link>

            @unless (request()->routeIs('dashboard'))
            <x-responsive-nav-link :href="route('dashboard')" icon_before="heroicon-o-home">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @endunless

            <x-responsive-nav-link :href="route('requests')" :active="request()->routeIs('requests')" icon_before="heroicon-o-user-add">
                {{ __('Requests') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('favorites')" :active="request()->routeIs('favorites')" icon_before="heroicon-o-star">
                {{ __('Favorites') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('blocklist')" :active="request()->routeIs('blocklist')" icon_before="heroicon-o-ban">
                {{ __('Blocklist') }}
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" icon_before="heroicon-o-logout">
                  {{ __('Logout') }}
              </x-responsive-nav-link>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>