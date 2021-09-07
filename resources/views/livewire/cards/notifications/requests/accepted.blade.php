<div class="w-full max-w-sm px-4 pt-4 pb-2 mx-1 my-1 bg-white border rounded-md sm:w-80 lg:w-96">
    <div class="flex">
        <div class="flex flex-col items-center justify-center mr-3 lg:mr-5">
            <div class="block w-12 h-12 mb-2 overflow-hidden bg-blue-200 rounded-full sm:w-14 sm:h-14 lg:w-16 lg:h-16">
                @if ($user->avatar)
                <img id="avatar_img" src="{{ $user->avatarPath }}" alt="avatar image" width="100%" height="100%" class="h-full">
                @else
                <svg id="pl-ava" class="w-full h-full text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                @endif
            </div>
        </div>
        <div>
            <p class="text-sm">
                <span class="font-semibold">{{ $user->fullname }}</span> accepted your roommate request
                <a href="{{ route('profile.view', [ 'user'=> $user ] ) }}" class="inline-block px-1 text-xs font-medium text-blue-600 transition duration-150 ease-in-out sm:text-sm hover:text-blue-800 focus:outline-none focus:text-blue-600">
                    View Profile
                </a>
            </p>
        </div>
    </div>

    <div class="flex items-center justify-between pb-1">
        <div class="text-xs font-medium text-gray-600">
            {{ $notification->created_at->diffForHumans() }}
        </div>
        <x-button-primary wire:click="" class="ml-auto mr-1.5">
            <x-slot name="svgPath">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </x-slot>
            Contact
        </x-button-primary>
    </div>
</div>