@if (!$this->isBlocker)
<div class="col-span-1 px-4 pt-4 pb-2 bg-white border rounded-md">
    <div class="flex">
        <div class="flex flex-col items-center justify-center mt-1 mr-3 lg:mr-5">
            <div class="block w-16 h-16 mb-2 overflow-hidden bg-blue-200 rounded-full md:w-18 md:h-18">
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
            <div class="font-semibold ">
                <p class="text-sm leading-tight sm:text-base">{{ $user->fullname }}</p>
                <div class="text-xs font-normal text-gray-600 xs:text-sm">
                    <p>{{ $user->course->name }}</p>
                </div>
            </div>
            <a href="{{ route('profile.view', [ 'user'=> $user ] ) }}" class="text-xs font-medium text-blue-600 transition duration-150 ease-in-out sm:text-sm hover:text-blue-800 focus:outline-none focus:text-blue-600">
                View Profile
            </a>
        </div>
        <div class="ml-auto">
            <button wire:click="$emit('blockOrReport', {{ $user->id }}, '{{ $user->fullname }}')" aria-label="block or report user menu button" title="block or report user menu button" class="inline-flex items-center justify-center p-1 text-gray-500 transition duration-150 ease-in-out rounded-md hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                </svg>
            </button>
        </div>
    </div>

    <div class="flex items-center justify-between px-2 py-2 mb-2 border rounded-md">
        <div>
            <div class="flex items-center text-xs font-semibold text-gray-500">RENT
                <span class="ml-1 font-serif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="text-lg font-semibold leading-none text-gray-700">₦{{ number_format(($user->min_budget + $user->max_budget)/2) }}</div>
        </div>

        <div class="text-xs text-gray-600">
            @foreach ($user->towns->take(3) as $town)
            <span class="bg-gray-100 rounded-md mr-0.5 py-0.5 px-2 text-gray-600">{{ ucfirst($town['name']) }}</span>
            @endforeach
        </div>
    </div>
    <div class="flex items-center justify-between pb-1">
        <div class="flex items-center px-1 pt-1 text-xs font-semibold text-red-700 rounded-md sm:pt-0 lg:text-sm">
            <span class="" style="padding-right: 2px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-4 h-4" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                </svg>
            </span>
            {{$user->similarity_score}}%
        </div>
        <div class="flex justify-end pt-1 sm:pt-0">
            @include('components.livewire.includes.favoriting-sxn')
            @include('components.livewire.includes.requesting-sxn')
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
    <!-- <div class="hidden px-4 py-4 mt-6 bg-gray-800 border rounded-md lg:mt-4 sm:block">
        <div class="inline-flex items-center justify-center px-2 mb-2 text-center text-gray-100 bg-gray-700 rounded-md">AD</div>
        <p class="text-xs text-gray-200">Buy one hosting plan and get a free domain. Offer last till the 4th of July on <span class="font-bold text-blue-300">Hostinger.com</span></p>
    </div>     -->
</div>
@endif