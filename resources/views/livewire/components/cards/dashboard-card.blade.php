<div class="w-full col-span-1 bg-white border divide-y rounded-md shadow">
    <div class="flex items-center justify-between px-4 py-4">
        <div class="flex items-center">
            <div class="w-10 h-10 mr-2 overflow-hidden border-2 rounded-full border-primary-500 lg:mr-3">
                <img id="avatar" src="{{ $user->avatar_path ?? asset('images/avatar_placeholder.png') }}" alt="{{ $user->firstname }}'s avatar" class="w-full">
            </div>
            <div class="max-w-[160px]">
                <p class="overflow-x-hidden text-base font-semibold text-secondary-700 text-ellipsis">
                    {{ $user->fullname }}
                </p>
                <p title="{{ $user->course->name }}" class="overflow-x-hidden text-sm text-ellipsis text-secondary-500">
                    {{ $user->course->name }}
                </p>
            </div>
        </div>
        <div class="flex items-center">
            <x-filament-support::link href="{{ route('profile.view', ['user' => $user]) }}" size="sm" aria-label="View {{ $user->fullname }} profile" title="View {{ $user->fullname }} profile">
                View profile
            </x-filament-support::link>
        </div>
    </div>

    <div class="flex flex-row-reverse items-center">
        <p title="similarity percentage" class="px-4 py-4 ml-auto text-xs font-semibold border-l text-danger-700">{{ $user->similarity_score }}%</p>

        <div class="pl-4 mr-auto">
            <p class="font-semibold">
                <span class="text-sm text-secondary-500">Budget:</span> ${{ $user->min_budget }} - ${{ $user->max_budget }}
            </p>
        </div>
    </div>

    <div class="flex items-center gap-2 px-4 py-4">
        <x-livewire.includes.favoriting-sxn :user="$user" />
        <x-livewire.includes.requesting-sxn :user="$user" />
        <x-filament-support::icon-button 
            wire:click='showReportOrBlockModal' 
            wire:loading.attr="disabled" 
            style="border-radius: 0.5rem;" 
            class="border border-secondary-300 rounded-lg disabled:cursor-not-allowed disabled:pointer-events-none shrink-0 " 
            color="secondary" 
            size="sm" 
            icon="heroicon-s-dots-horizontal" 
            aria-label="show user menu" 
            title="show user menu"
        />
    </div>
</div>