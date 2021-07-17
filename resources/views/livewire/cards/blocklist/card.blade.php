@isset($user)
<div class="w-full px-4 pt-4 pb-2 mx-2 my-2 bg-white border rounded-md md:w-80 lg:w-96">
    <div class="flex pb-2 mb-1 border-b">
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
        <div>
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
    </div>

    <div class="flex items-center justify-between pt-2">
        <p class="text-sm font-semibold text-gray-600">{{ $user->pivot->created_at->diffForHumans() }}</p>
        <button wire:click="unblockUser()" class="inline-flex items-center mr-1.5  px-2 py-1 text-xs xs:text-sm text-blue-100 transition duration-150 ease-in-out bg-blue-600 rounded-md hover:text-blue-100 hover:bg-blue-800 focus:outline-none focus:bg-blue-800 focus:text-blue-100">
            <span class="pr-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-4 h-4" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
            Unblock
        </button>   
    </div>
</div>
@endisset
