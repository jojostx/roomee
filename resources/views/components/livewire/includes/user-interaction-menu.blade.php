<div 
  x-data='{ 
    isOpen: false, 
    user_id: null, 
    user_name: null,
    is_blocked: false,
    showReportUserModal: () => {
      if($data.user_id)
      {
        Livewire.emit(
          "openModal", 
          "components.modals.user-reporting-modal", 
          { user: $data.user_id }
        );
      }
      $data.isOpen = false;
    },
    showBlockUserModal: () => {
      if($data.user_is_blocked)
      {
        $data.is_blocked = true;
      }
      if($data.user_id)
      {
        Livewire.emit(
          "openModal", 
          "components.modals.user-blocking-modal", 
          { user: $data.user_id }
        );
      }
      $data.isOpen = false;
    },
  }'
  x-trap.noscroll="isOpen" 
  x-on:close-modal.window="if ($event.detail.id === 'user-interaction-menu') isOpen = false" 
  x-on:open-modal.window="if ($event.detail.id === 'user-interaction-menu')
    {
        isOpen = true;
        user_id = $event.detail.user_id;
        user_name = $event.detail.user_name 
    }"
  class="inline filament-modal"
  role="dialog" 
  aria-modal="true" 
>
  <div 
    x-cloak
    x-show="isOpen"
    x-transition:enter="ease duration-300" 
    x-transition:enter-start="opacity-0" 
    x-transition:enter-end="opacity-100" 
    x-transition:leave="ease duration-300" 
    x-transition:leave-start="opacity-100" 
    x-transition:leave-end="opacity-0" 
    class="fixed inset-0 z-40 flex items-center min-h-screen p-4 overflow-x-hidden overflow-y-auto transition"
  >
    <div 
      x-on:click="$dispatch('close-modal', { id: 'user-interaction-menu' })" 
      class="fixed inset-0 w-full h-full cursor-pointer filament-modal-close-overlay bg-black/50"
      aria-hidden="true" 
    >
    </div>

    <div
      x-show="isOpen"
      x-on:keydown.window.escape="$dispatch('close-modal', { id: 'user-interaction-menu' })"
      x-transition:enter="ease duration-300"
      x-transition:leave="ease duration-300" 
      x-transition:enter-start="translate-y-8" 
      x-transition:enter-end="translate-y-0" 
      x-transition:leave-start="translate-y-0" 
      x-transition:leave-end="translate-y-8" 
      class="relative w-full my-auto cursor-pointer pointer-events-none"
    >
      <div class="relative w-full max-w-sm mx-auto bg-white rounded-lg cursor-default pointer-events-auto filament-modal-window">
        <ul class="py-2 filament-modal-content">
          <x-responsive-nav-link 
            icon_before="heroicon-o-flag" 
            x-on:click="showReportUserModal"
          >
            <p>
              {{ __('Report') }}&nbsp;<span class="font-semibold user-name" x-text="user_name">Unknown</span>
            </p>
          </x-responsive-nav-link>
          <x-responsive-nav-link 
            icon_before="heroicon-o-ban" 
            x-on:click="showBlockUserModal"
          >
            <p>
              <span x-show="is_blocked">
                {{ __('Unblock') }}
              </span>
              <span x-show="!is_blocked">
                {{ __('Block') }}
              </span>
              <span class="font-semibold user-name" x-text="user_name">
                Unknown
              </span>
            </p>
          </x-responsive-nav-link>
        </ul>
      </div>
    </div>
  </div>
</div>