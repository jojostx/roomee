@if (!$this->isBlocker)
<div class="col-span-1 px-4 pt-4 pb-2 bg-white border rounded-md">
    <div class="flex mb-2">
        <div class="flex flex-col items-center justify-center mt-1 mr-3 lg:mr-5">
            <div class="block mb-2 overflow-hidden bg-blue-200 rounded-full w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20">
                @if ($user->avatar)
                <img id="avatar_img" src="{{ $user->avatarPath }}" alt="avatar image" width="100%" height="100%" class="h-full">
                @else
                <svg id="pl-ava" class="w-full h-full text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                @endif
            </div>
        </div>
        <div class="mb-1">
            <div class="mb-1 font-semibold sm:mb-2">
                <p class="text-sm leading-tight sm:text-base">{{ $user->fullname }}</p>
                <div class="text-xs font-normal text-gray-600 xs:text-sm">
                    <p>{{ $user->course->name }}</p>
                </div>
            </div>
            <a href="{{ route('profile.view', [ 'user'=> $user ] ) }}" style="border-width: 1.5px;" class="px-2 py-1 text-xs sm:text-sm text-blue-800 border-1.5 border-blue-700 rounded-md transition duration-150 ease-in-out hover:text-blue-600 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 focus:text-blue-600">
                View Profile
            </a>
        </div>
        <div class="ml-auto">
            <button wire:click="$emit('blockOrReport', {{ $user->id }}, '{{ $user->fullname }}')" aria-label="options menu button" title="options menu button" class="inline-flex items-center justify-center p-1 text-gray-500 transition duration-150 ease-in-out rounded-md hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
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
        <div class="flex flex-wrap items-end overflow-hidden">
            @foreach ($user->hobbies as $hobby)
                @if ($loop->iteration <= 3 )
                    <div class="inline-flex items-center justify-center px-2 py-1 mb-2 mr-2 text-sm text-gray-800 bg-gray-200 rounded-md">{{ $hobby['name'] }}</div>
                @endif
            @endforeach
            <span class="text-lg font-extrabold text-gray-400 mb-0.5 tracking-widest">...</span>
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
        <div class="flex flex-wrap items-end overflow-hidden">
            @foreach ($user->dislikes as $dislike)
            @if ($loop->iteration <= 3 )
            <div class="inline-flex items-center justify-center px-2 py-1 mb-2 mr-2 text-sm text-gray-800 bg-gray-200 rounded-md">{{ $dislike['name'] }}</div>
            @endif
            @endforeach
            <span class="text-lg font-extrabold text-gray-400 mb-0.5 tracking-widest">...</span>
        </div>
    </div>

    <div class="flex items-center justify-between pt-2 pb-1">
        <div class="flex items-center px-1 pt-1 text-xs font-semibold text-red-700 rounded-md sm:pt-0 lg:text-sm">
            <span class="" style="padding-right: 2px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-4 h-4" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                </svg>
            </span>
            {{$user->similarity_score}}%
        </div>
        <div class="flex justify-end pt-1 sm:pt-0" >
            @if (auth()->user()->favorites->contains($user))
            <button wire:click="unfavorite()" class="inline-flex items-center mr-1.5 px-2 py-1 text-xs xs:text-sm text-blue-600 transition duration-150 ease-in-out bg-blue-100 rounded-md hover:text-blue-700 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 focus:text-blue-700">
                <span class="pr-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-4 h-4" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </span>
                Unfavorite
            </button>
            @else                
            <button wire:click="favorite()" class="inline-flex items-center mr-1.5 px-2 py-1 text-xs xs:text-sm text-blue-600 transition duration-150 ease-in-out bg-blue-100 rounded-md hover:text-blue-700 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 focus:text-blue-700">
                <span class="pr-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-4 h-4" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </span>
                Favorite
            </button>
            @endif

            <button class="inline-flex items-center justify-start px-2 py-1 text-xs text-blue-600 transition duration-150 ease-in-out bg-blue-100 rounded-md xs:text-sm hover:text-blue-700 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 focus:text-blue-700">
                <span class="pr-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-4 h-4" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </span>
                Request
            </button>
        </div>
    </div>
</div>  
@else
<div class="col-span-1 pb-2 bg-white border rounded-md sm:max-h-40">
    <div class="flex px-4 pt-4 mb-2">
        <div class="flex flex-col items-center justify-center mt-1 mr-3 lg:mr-5">
            <div class="block mb-2 overflow-hidden bg-blue-200 rounded-full w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20">
                @if ($user->avatar)
                <img id="avatar_img" src="{{ $user->avatarPath }}" alt="avatar image" width="100%" height="100%" class="h-full">
                @else
                <svg id="pl-ava" class="w-full h-full text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                @endif
            </div>
        </div>
        <div class="mb-1">
            <div class="mb-1 font-semibold sm:mb-2">
                <p class="text-sm leading-tight sm:text-base">{{ $user->fullname }}</p>
                <div class="text-xs font-normal text-gray-600 xs:text-sm">
                    <p>{{ $user->course->name }}</p>
                </div>
            </div>
            <a href="{{ route('profile.view', [ 'user'=> $user ] ) }}" style="border-width: 1.5px;" class="px-2 py-1 text-xs sm:text-sm text-blue-800 border-1.5 border-blue-700 rounded-md transition duration-150 ease-in-out hover:text-blue-600 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 focus:text-blue-600">
                View Profile
            </a>
        </div>
        <div class="ml-auto">
            <button wire:click="$emit('blockOrReport', {{ $user->id }}, '{{ $user->fullname }}')" aria-label="options menu button" title="options menu button" class="inline-flex items-center justify-center p-1 text-gray-500 transition duration-150 ease-in-out rounded-md hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                </svg>
            </button>
        </div>
    </div>
    <div class="px-4 pb-2 bg-white rounded-md">
        <p class="text-xs text-gray-500">You are blocked from viewing <span class="font-semibold">{{ $user->firstname }}'s</span> complete profile and sending them roommate request.
        <a href="{{ route('faqs') }}#q_4" class="text-blue-700">Learn more</a>
    </p>
    </div>    
    <div class="hidden px-4 py-4 mt-6 bg-gray-800 border rounded-md lg:mt-4 sm:block">
        <div class="inline-flex items-center justify-center px-2 mb-2 text-center text-gray-100 bg-gray-700 rounded-md">AD</div>
        <p class="text-xs text-gray-200">Buy one hosting plan and get a free domain. Offer last till the 4th of July on <span class="font-bold text-blue-300">Hostinger.com</span></p>
    </div>    
</div>
@endif