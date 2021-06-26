@can('view', $user)
<div class="col-span-1 px-4 pt-4 pb-2 bg-white border rounded-md">
    <div class="flex mb-2">
        <div class="flex flex-col items-center justify-center mt-1 mr-3 lg:mr-5">
            <div class="block w-16 h-16 mb-2 overflow-hidden bg-blue-200 rounded-full lg:w-20 lg:h-20">
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
            <div class="mb-2 font-semibold">
                <p class="leading-tight">{{ $user->fullname }}</p>
                <div class="text-sm font-normal text-gray-600">
                    <p>{{ $user->course->name }}</p>
                </div>
            </div>
            <a href="{{ route('profile.view', [ 'user'=> $user ] ) }}" style="border-width: 1.5px;" class="px-2 py-1 text-sm text-blue-800 border-1.5 border-blue-400 rounded-md transition duration-150 ease-in-out hover:text-blue-600 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 focus:text-blue-600">
                View Profile
            </a>
        </div>
        <div class="ml-auto">
            <button wire:click="$emit('blockOrReport', {{ $user->id }}, '{{ $user->fullname }}')" aria-label="options menu button" title="options menu button" class="inline-flex items-center justify-center p-1 text-gray-500 transition duration-150 ease-in-out rounded-md hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600">
                <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                </svg>
            </button>
        </div>
    </div>

    <div class="py-1.5 border-t border-b">
        <!-- <p class="mb-1 text-sm font-semibold text-gray-500">Hobbies & Interests</p> -->
        <x-livewire.label>
            <x-slot name='svgicon'>
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
            </x-slot>
            Hobbies and Interests
        </x-livewire.label>
        <div class="flex overflow-hidden">
            @foreach ($user->hobbies as $hobby)
            <div class="inline-flex items-center justify-center px-2 py-1 mb-2 mr-2 text-sm text-gray-800 bg-gray-200 rounded-md">{{ $hobby['name'] }}</div>
            @endforeach
        </div>
    </div>

    <div class="py-1.5 border-b">
        <!-- <p class="mb-1 text-sm font-semibold text-gray-500">Dislikes</p> -->
        <x-livewire.label>
            <x-slot name='svgicon'>
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />
            </x-slot>
            Dislikes
        </x-livewire.label>
        <div class="flex flex-wrap overflow-hidden">
            @foreach ($user->dislikes as $dislike )
            <div class="inline-flex items-center justify-center px-2 py-1 mb-2 mr-2 text-sm text-gray-800 bg-gray-200 rounded-md">{{ $dislike['name'] }}</div>
            @endforeach
        </div>
    </div>

    <div class="flex items-center justify-between pt-2">
        <p class="text-xs font-semibold text-gray-600 lg:text-sm">88% Match</p>
        <div>
            <button class="inline-flex items-center mr-1.5 px-2 py-1 text-xs text-blue-600 transition duration-150 ease-in-out bg-blue-100 rounded-md hover:text-blue-700 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 focus:text-blue-700">
                <span class="pr-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-4 h-4" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </span>
                Add to favorites
            </button>
            <button class="inline-flex items-center px-2 py-1 text-xs text-blue-600 transition duration-150 ease-in-out bg-blue-100 rounded-md hover:text-blue-700 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 focus:text-blue-700">
                <span class="pr-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-4 h-4" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </span>
                Send Request
            </button>
        </div>
    </div>
</div>
@elsecannot('view', $user)
<div class="hidden" style="display: none;">
</div>
@endcan

