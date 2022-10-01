<div>
    @if ($action == App\Enums\OnUserAction::BLOCK)
    <div>
        <div class="flex items-center justify-center py-3 border-b border-gray-300">
            <p class="font-semibold text-gray-700">
                Block <span class="user-name">{{ $user->fullname }}</span>?
            </p>
        </div>
        <div class="px-4 py-2">
            <p class="text-gray-600">
                <span class="font-medium text-gray-900">{{ $user->fullname }}</span> will no longer be able to view your profile or send you a roommate request.
            </p>
            <div class="flex justify-end pt-2">
                <button wire:click="$emit('closeModal')" class="px-1 py-2 mr-4 font-semibold hover:text-primary-500 focus:text-primary-600 focus:outline-none">Cancel</button>
                <button wire:click="submit" class="px-1 py-2 font-semibold text-red-500 hover:text-red-700 focus:text-red-700 focus:outline-none">Block</button>
            </div>
        </div>
    </div>
    @elseif ($action == App\Enums\OnUserAction::REPORT)
    <div>
        <div class="flex items-center justify-center py-3 border-b border-gray-300">
            <p class="font-semibold text-gray-700">
                Report <span class="user-name">{{ $user->fullname }}</span>?
            </p>
        </div>
        <div class="py-1 pt-2 text-base">
            <ul class="px-2">
                @foreach ($reports as $key => $value)
                <li>
                    <label for="report_{{ $key }}" class="flex items-center justify-between px-2 py-2 border-b cursor-pointer">
                        <p>{{ $value }}</p>
                        <input type="checkbox" id="report_{{ $key }}" value="{{ $key }}" wire:model="selectedReports">
                    </label>
                </li>
                @endforeach
            </ul>
            <div class="flex justify-end px-4">
                <button wire:click="$emit('closeModal')" class="px-1 py-2 mr-4 font-semibold hover:text-primary-500 focus:text-primary-600 focus:outline-none">Cancel</button>
                <button wire:click="submit" class="px-1 py-2 font-semibold text-red-500 hover:text-red-700 focus:text-red-700 focus:outline-none">Block</button>
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
    </div>
    @else
    <ul class="py-2">
        <x-responsive-nav-link wire:click="reportUser()">
            <span class="inline p-2 mr-4 rounded-full w-9 h-9 bg-secondary-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-full" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                </svg>
            </span>
            <p>
                {{ __('Report') }}&nbsp;<span class="font-semibold user-name">{{ $user->fullname }}</span>
            </p>
        </x-responsive-nav-link>
        <x-responsive-nav-link wire:click="blockUser()">
            <span class="inline p-2 mr-4 rounded-full w-9 h-9 bg-secondary-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-full" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </span>
            <p>
                {{ __('Block') }}&nbsp;<span class="font-semibold user-name">{{ $user->fullname }}</span>
            </p>
        </x-responsive-nav-link>
    </ul>
    @endif
</div>