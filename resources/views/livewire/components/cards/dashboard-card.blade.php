<div class="w-full col-span-1 bg-white border divide-y rounded-md shadow">
    <div class="flex items-center justify-between px-4 py-2">
        <div class="flex items-center">
            <div class="w-10 h-10 mr-2 overflow-hidden border-2 rounded-full border-primary-500 lg:mr-3">
                <img id="avatar" src="{{ $user->avatar_path ?? asset('images/avatar_placeholder.png') }}" alt="{{ $user->firstname }}'s avatar" class="w-full">
            </div>
            <div class="max-w-[160px]">
                <p class="overflow-x-hidden text-base font-semibold text-gray-700 text-ellipsis">
                    {{ $user->fullname }}
                </p>
                <p title="{{ $user->email }}" class="overflow-x-hidden text-sm text-ellipsis text-secondary-500">
                    {{ $user->email }}
                </p>
            </div>
        </div>
        <div class="flex items-center">
            <x-filament-support::icon-button size="md" icon="heroicon-o-dots-vertical">
                <x-slot name="label">
                    options trigger
                </x-slot>
            </x-filament-support::icon-button>
        </div>
    </div>

    <div class="flex flex-row-reverse items-center gap-2 px-4 py-2">
        <span class="mr-auto text-xs font-semibold text-danger-700">{{ $user->similarity_score }}%</span>
    </div>

    <div class="flex items-center gap-2 px-4 py-2">
        <x-livewire.includes.favoriting-sxn :user="$user" />
        <x-livewire.includes.requesting-sxn :user="$user" />
        <button class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg shrink-0 focus:outline-none hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" type="button">
            <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
            </svg>
        </button>
    </div>
</div>