<button title="Open Notifications" type="button" class="relative flex items-center justify-center w-10 h-10 text-sm font-medium text-center text-gray-500 rounded-md hover:text-gray-900 dark:hover:text-white dark:text-gray-400 filament-icon-button hover:bg-gray-500/5 focus:outline-none focus:text-primary-500 focus:bg-primary-500/10" aria-expanded="false">
  <span class="sr-only">
    Notifications trigger
  </span>

  <x-heroicon-s-bell class="w-6 h-6 filament-icon-button-icon" />

  @if ($unreadNotificationsCount)
  <span class="absolute block w-3 h-3 border-2 border-white rounded-full bg-danger-500 top-1 right-2 dark:border-gray-900"></span>
  @endif
</button>