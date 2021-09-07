@isset($user)
<div class="w-full max-w-md px-4 pt-4 pb-2 my-2 bg-white border rounded-md lg:w-96">
    <div class="flex mb-2">
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
        <div class="font-semibold">
            <p class="text-sm leading-tight sm:text-base">{{ $user->fullname }}</p>
            <div class="text-xs font-normal leading-none text-gray-600 xs:text-sm">
                <p>{{ $user->course->name }}</p>
            </div>
            <a href="{{ route('profile.view', [ 'user'=> $user ] ) }}" class="inline-block text-xs font-medium text-blue-600 transition duration-150 ease-in-out sm:text-sm hover:text-blue-800 focus:outline-none focus:text-blue-600">
                View Profile
            </a>
        </div>
    </div>

    <div class="flex items-center justify-between pb-1">
        <p class="text-xs font-medium text-gray-600">{{ $request->created_at->diffForHumans() }}</p>
        @if (auth()->user()->isRoommateWith($user))
        <x-button-primary class="ml-auto">
            <x-slot name="svgPath">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </x-slot>
            Contact
        </x-button-primary>
        @else
        <button wire:click="showDeleteRequestPopup()" class="inline-flex items-center px-1 py-1 text-xs font-semibold text-blue-100 transition duration-150 ease-in-out bg-blue-600 rounded-md xs:px-2 sm:text-sm hover:text-blue-100 hover:bg-blue-800 focus:outline-none focus:bg-blue-800 focus:text-blue-100">
            <span class="pr-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-3 h-3 sm:w-4 sm:h-4" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                </svg>
            </span>
            Delete Request
        </button>
        @endif
    </div>
</div>
@endisset