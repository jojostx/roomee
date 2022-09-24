@php
    $darkMode = config('filament.dark_mode');
@endphp

<div 
  x-data="{
        isLoading: false,

        shouldCheckUniqueSelection: false,
        
        readNotifications: @js($readNotifications),

        init: function () {
          window.addEventListener('notification-recieved', () => { new Audio(`{{ url('assets/notification-sound.mp3') }}`).play() });

          $watch('readNotifications', () => {
              if (! this.shouldCheckUniqueSelection) {
                  this.shouldCheckUniqueSelection = true

                  return
              }

              this.readNotifications = [...new Set(this.readNotifications)];

              this.shouldCheckUniqueSelection = false;
          });
        },

        markNotificationAsRead: async function (key) {
          if (this.isNotificationRead(key)) {
              return
          }

          this.readNotifications.push(key)
          await $wire.markNotificationAsRead(key)
        },

        markNotificationAsUnread: async function (key) {
          let index = this.readNotifications.indexOf(key)

          if (index === -1) {
              return
          }

          this.readNotifications.splice(index, 1)
          await $wire.markNotificationAsUnread(key)
        },

        markAllNotificationAsRead: async function () {
            this.isLoading = true

            this.readNotifications = (await $wire.markAllNotificationsAsRead()).map((key) => key.toString())

            console.log(this.readNotifications)

            this.isLoading = false
        },

        toggleNotificationRead: async function (key) {
            if (this.isNotificationRead(key)) {
                await this.markNotificationAsUnread(key)

                return
            }

            await this.markNotificationAsRead(key)
        },

        isNotificationRead: function (key) {
            return this.readNotifications.includes(key)
        },
    }"
  wire:poll.30s
  class="relative flex items-center"
  >
  <button @click="$dispatch('open-modal', { id: 'database-notifications-panel' });" title="Open Notifications" type="button" class="relative flex items-center justify-center w-10 h-10 text-sm font-medium text-center text-gray-500 rounded-md hover:text-gray-900 dark:hover:text-white dark:text-gray-400 filament-icon-button hover:bg-gray-500/5 focus:outline-none focus:text-primary-500 focus:bg-primary-500/10" aria-expanded="false" aria-controls="panel-1uT4AAWg">
    <span class="sr-only">
      Notifications
    </span>

    <x-heroicon-s-bell class="w-6 h-6 filament-icon-button-icon" />

    @if ($this->hasUnreadNotifications)
    <span class="absolute block w-3 h-3 border-2 border-white rounded-full bg-danger-500 top-1 right-2 dark:border-gray-900"></span>
    @endif
  </button>

  <!-- Dropdown menu -->
  <x-filament-support::modal
    id="database-notifications-panel"
    :dark-mode="config('notifications.dark_mode')"
    :close-button="false"
    slide-over
  >
    <div class="flex items-center justify-between w-full">
      <div>
        <p class="text-lg font-semibold text-gray-700 dark:text-white">
          Notifications
        </p>
        
        @if ($this->hasUnreadNotifications)
        <x-filament::link tag="button" size="sm" x-on:click="markAllNotificationAsRead" style="font-size: 0.75rem; line-height: 1rem;">
          {{ __('Mark all as Read') }}
        </x-filament::link>
        @endif
      </div>

      <x-filament-support::icon-button x-on:click="$dispatch('close-modal', { id: 'database-notifications-panel' });" :dark-mode="$darkMode" icon="heroicon-o-x" class="-my-2">
        <x-slot name="label">
          close notification
        </x-slot>
      </x-filament-support::icon-button>
    </div>

    <div class="h-full py-2 overflow-y-auto text-sm text-gray-700 dark:text-gray-200">
      @forelse ($notifications as $notification)
        <div>
            {{ $notification->read_at }}
        </div>
        @if ($loop->last)
          <div class="flex items-center justify-between px-3">
            <hr class="w-full">
            <p tabindex="0" class="flex flex-shrink-0 px-3 py-8 text-sm leading-normal text-gray-500 focus:outline-none">Thats it for now :)</p>
            <hr class="w-full">
          </div>
        @endif
      @empty
        <x-notifications::database.modal.empty-state />
      @endforelse
    </div>
  </x-filament-support::modal>
</div>