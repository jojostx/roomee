<div class="w-full col-span-1 bg-white border divide-y rounded-md shadow">
    <div class="flex items-center justify-between px-4 py-2">
        <div class="flex items-center">
            <div class="mr-2 overflow-hidden border-2 rounded-full border-primary-500 lg:mr-3 w-9 h-9">
                <img id="avatar" src="{{ $user->avatar_path ?? asset('images/avatar_placeholder.png') }}" alt="{{ $user->firstname }}'s avatar" width="100%" height="100%">
            </div>
            <div class="max-w-[140px] overflow-ellipsis overflow-x-clip">
                <p>
                    {{ $user->fullname }}
                </p>
                <p class="text-sm leading-none text-secondary-600">
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
        <x-livewire.includes.requesting-sxn :user="$user" />
        <x-livewire.includes.favoriting-sxn :user="$user" />
        <span class="mr-auto text-xs font-semibold text-danger-700">{{ $user->similarity_score }}%</span>
    </div>
</div>