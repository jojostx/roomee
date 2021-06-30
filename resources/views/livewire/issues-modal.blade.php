<div id="toast" class="fixed top-0 left-0 z-50 items-center justify-center w-full h-full @if($show) flex @else hidden @endif">
    @if ($show)
    <div class="absolute top-0 z-10 w-full h-full bg-gray-900 opacity-40" wire:click="reset_"></div>
    <div class="z-20 w-64 overflow-hidden bg-white rounded-md lg:w-72">
        @if ($action === '')
        <ul class="py-2">
            <x-responsive-nav-link wire:click="$set('action', 'report')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline w-5 h-5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                </svg>
                {{ __('Report') }} <span class="font-semibold user-name">{{ $username }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link wire:click="$set('action', 'block')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline w-5 h-5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
                {{ __('Block') }} <span class="font-semibold user-name">{{ $username }}</span>
            </x-responsive-nav-link>
        </ul>
        @endif
        @if ($action === 'block')
        <div class="px-4 py-2 text-base">
            <p class="mb-2 font-semibold">
                Block <span class="user-name">{{ $username }}</span>?
            </p>
            <p class="mb-2">
                <span class="font-semibold user-name">{{ $username }}</span> will no longer be able to view your profile or send you match request.
            </p>
            <div class="flex justify-end">
                <button wire:click="reset_" class="px-2 py-2 mr-2 font-semibold hover:text-blue-500 focus:text-blue-600">Cancel</button>
                <form method="post" @submit.prevent="">
                    @csrf
                    <button wire:click="submit" type="button" class="px-2 py-2 font-semibold hover:text-blue-500 focus:text-blue-600">Block</button>
                </form>
            </div>
        </div>
        @endif
        @if ($action === 'report')
        <div class="py-1 pt-2 text-base">
            <ul class="px-2 text-sm">
                @foreach ($reports as $key => $value)
                <li>
                    <label for="report_{{ $key  }}" class="flex items-center justify-between px-2 py-2 border-b cursor-pointer">
                        <p>{{ $value }}</p>
                        <input type="checkbox" id="report_{{ $key }}" value="{{ $key }}" wire:model="selectedReports">
                    </label>
                </li>   
                @endforeach  
            </ul>
            <div class="flex justify-end px-2">
                <button wire:click="reset_" class="px-1 py-2 mr-2 font-semibold hover:text-blue-500 focus:text-blue-600 focus:outline-none">Cancel</button>
                <button wire:click="submit" class="px-1 py-2 font-semibold hover:text-blue-500 focus:text-blue-600 focus:outline-none">Report</button>
            </div>
            @if ($errors->any())
            <div class="bg-red-100 border-t border-red-500">
                <div class="px-4 py-2 text-red-600">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
    @endif
</div>