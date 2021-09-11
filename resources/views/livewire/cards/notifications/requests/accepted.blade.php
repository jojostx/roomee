<div class="w-full max-w-md px-3 py-3 mb-1 bg-white border rounded-md lg:w-96">
    <div class="px-2 py-1 mb-2 text-xs text-blue-700 bg-blue-100 rounded-md md:text-sm">
        <p><span class="font-medium"> {{$user->fullname}} </span>Accepted Your Roommate request</p>
    </div>
    <div class="flex items-center">
        <div class="flex flex-col items-center justify-center mr-3 lg:mr-5">
            <div class="block w-12 h-12 overflow-hidden bg-blue-200 rounded-full sm:w-14 sm:h-14 lg:w-16 lg:h-16">
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
            <p class="text-sm leading-none">
                <span class="font-semibold">{{ $user->fullname }}</span>
                <span class="px-1 text-gray-700">.</span>
                <span class="inline-block text-xs">
                    {{ $notification->created_at->diffForHumans(null, null, true) }}
                </span>
            </p>
            <a href="{{ route('profile.view', [ 'user'=> $user ] ) }}" class="text-xs font-medium text-blue-600 transition duration-150 ease-in-out sm:text-sm hover:text-blue-800 focus:outline-none focus:text-blue-600">
                View Profile
            </a>
        </div>
    </div>
</div>