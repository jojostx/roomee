<div class="w-11/12 m-auto mt-6 mb-6">
    <div class="md:grid md:grid-cols-7 md:mt-12">
        @can('update', $user)
        <div class="flex items-center justify-between mb-6 sm:w-64 md:72 col-span-full lg:fixed">
            <p class="text-2xl font-semibold">Your Profile</p>
            <a href="{{ route('profile.update')}}" class="px-3 py-1 text-sm font-medium border border-gray-500 rounded-md hover:shadow hover:border-blue-700 hover:text-blue-800 focus:border-blue-600 focus:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Edit Profile
            </a>
        </div>
        @elsecannot('update', $user)
        <div class="flex items-center justify-between mb-5 sm:w-64 md:72 col-span-full lg:fixed sm:block">
            <ul wire:ignore class="flex-col hidden w-full px-2 py-2 my-2 overflow-y-auto font-semibold text-gray-800 list-none bg-gray-100 border rounded-md shadow-sm lg:flex">
                <li class="w-full my-1">
                    <a href="#pers_info" id="li-pers" class="flex items-center px-2 py-2 rounded-md profile_links hover:bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-5 mr-3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Personal Information
                    </a>
                </li>
                <li class="w-full my-1">
                    <a href="#edu_info" id="li-edu" class="flex items-center px-2 py-2 rounded-md profile_links hover:bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 mr-3">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                        Educational Information
                    </a>
                </li>
                <li class="w-full my-1">
                    <a href="#apart_info" id="li-apart" class="flex items-center px-2 py-2 rounded-md profile_links hover:bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-5 mr-3" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Apartment Information
                    </a>
                </li>
            </ul>
        </div>
        @endcan
                 
        <div class="lg:h-4 md:mr-4 col-span-full lg:col-span-2">
        </div>
        <div class="max-w-3xl pb-6 sm:grid-cols-2 col-span-full lg:col-span-5">
            <div class="flex flex-col items-center justify-start px-4 py-4 mb-6 rounded-lg sm:flex-row bg-blue-50 sm:py-6">
                <div class="mb-4 sm:w-2/5 sm:mb-0">
                    <div class="flex flex-col items-center justify-center mt-1">
                        <div class="block w-24 h-24 mb-2 overflow-hidden bg-blue-200 rounded-full">
                            @if ($user->avatar)
                            <img id="avatar_img" src="{{ $user->avatarPath }}" alt="avatar image"  width="100%" height="100%" class="h-full">
                            @else
                            <svg id="pl-ava" class="w-full h-full text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-center w-full md:pt-2">
                    <div class="flex justify-start gap-4 mb-6">
                        <div class="w-1/2 mr-6">
                            <span class="text-lg font-semibold">{{ ucfirst($user->firstname) }}</span>
                            <label class="block text-sm text-gray-700">First Name</label>
                        </div>
                        <div>
                            <span class="text-lg font-semibold">{{ ucfirst($user->lastname) }}</span>
                            <label class="block text-sm text-gray-700">Last Name</label>
                        </div>
                    </div>
                    <div class="flex justify-start gap-4">
                        <div class="w-1/2 mr-6 overflow-hidden">
                            <span class="text-lg font-semibold">{{ ucfirst($user->email) }}</span>
                            <label class="block text-sm text-gray-700">Email Address</label>
                        </div>
                        <div>
                            <span class="text-lg font-semibold">{{ ucfirst($user->gender) }}</span>
                            <label class="block text-sm text-gray-700">Gender</label>
                        </div>
                    </div>
                </div>
            </div>
            @can('view', $user)
            <div class="mb-2 gap-x-6 lg:gap-y-2 sm:grid-cols-2">
                <div>
                    <label for="cover_photo" class="label">Cover photo</label>
                    <div id="cover_inp" class="overflow-hidden relative flex items-center justify-center px-4 py-4 mt-1 border-2 border-gray-300 @error('cover_photo') border-red-300 @enderror border-dashed rounded-md">
                        <div class="relative w-full text-center">
                            @if ($user->cover_photo)
                            <div wire:ignore>
                                <img src="{{ $user->coverPhotoPath }}" id="cover_out"  width="100%" height="100%" class="z-10 block w-full rounded-lg shadow-lg" alt="Your cover photo">
                            </div>
                            @else
                            <svg wire:ignore id="cover-svg" class="w-12 h-12 mx-auto text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid pt-6 gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-2">
                <div class="mb-4 col-span-full" id="pers_info">
                    <h1 class="text-xl font-semibold">Personal Information</h1>
                </div>
                <div class="relative px-2 py-4 mb-2 border border-gray-300 rounded-md col-span-full">
                    <p class="absolute z-10 px-2 bg-white -top-3.5  text-sm font-semibold text-gray-500">Bio</p>
                    <p class="px-1 text-blue-800">{{ $user->bio}}</p>
                </div>
                <div class="mb-2">
                    <x-livewire.label>
                        <x-slot name='svgicon'>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                        </x-slot>
                        Hobbies and Interests
                    </x-livewire.label>
                    <div class="px-2 pt-2 border border-gray-300 rounded-md">
                        @foreach ($user->hobbies as $hobby)
                        <span class="inline-flex items-center justify-center px-3 py-1 mb-2 mr-1 text-blue-800 bg-blue-100 rounded-md">{{ ucfirst($hobby['name']) }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="mb-2">
                    <x-livewire.label>
                        <x-slot name='svgicon'>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />
                        </x-slot>
                        Dislikes
                    </x-livewire.label>
                    <div class="px-2 pt-2 border border-gray-300 rounded-md">
                        @foreach ($user->dislikes as $dislike)
                        <span class="inline-flex items-center justify-center px-3 py-1 mb-2 text-red-800 bg-red-100 rounded-md">{{ ucfirst($dislike['name']) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="grid pt-6 gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-2">
                <div class="col-span-full" id="edu_info">
                    <h1 class="text-xl font-semibold">Educational Information</h1>
                </div>
                <div class="mb-2 col-span-full">
                    <x-livewire.label>
                        <x-slot name='svgicon'>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </x-slot>
                        Institute of Study
                    </x-livewire.label>
                    <p class="block py-2 pl-3 pr-4 text-base font-medium text-blue-800 border-l-4 border-blue-400 bg-blue-50">
                        {{ $user->school['name'] }}
                    </p>
                </div>
                <div class="col-span-1 mb-2">
                    <x-livewire.label>
                        <x-slot name='svgicon'>
                            <path fill="#fff" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path fill="#fff" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </x-slot>
                        Course of Study
                    </x-livewire.label>
                    <p class="block py-2 pl-3 pr-4 text-base font-medium text-blue-800 border-l-4 border-blue-400 bg-blue-50">
                        {{ $user->course['name'] }}
                    </p>
                </div>

                <div class="col-span-1 mb-2">
                    <x-livewire.label>
                        <x-slot name='svgicon'>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </x-slot>
                        Course Level
                    </x-livewire.label>
                    <label for="course" class="block mb-2 font-medium"></label>
                    <p class="block py-2 pl-3 pr-4 text-base font-medium text-blue-800 border-l-4 border-blue-400 bg-blue-50">
                        {{ $user['course_level'] }}
                    </p>
                </div>

            </div>
            <div class="grid pt-6 mb-4 group gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-2">
                <div class="col-span-full" id="apart_info">
                    <h1 class="text-xl font-semibold">Apartment Information</h1>
                </div>
                <div class="col-span-1 mb-2">
                    <x-livewire.label>
                        <x-slot name='svgicon'>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </x-slot>
                        Preferred property locations
                    </x-livewire.label>
                    <div class="px-2 pt-2 border border-gray-300 rounded-md">
                        @foreach ($user->towns as $town)
                        <span class="inline-flex items-center justify-center px-3 py-1 mb-2 text-blue-800 bg-blue-100 rounded-md">{{ ucfirst($town['name']) }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="col-span-1 mb-2">
                    <x-livewire.label>
                        <x-slot name='svgicon'>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </x-slot>
                        Number of Rooms
                    </x-livewire.label>
                    <label for="rooms" class="label"></label>
                    <p class="block py-2 pl-3 pr-4 text-base font-medium text-blue-800 border-l-4 border-blue-400 bg-blue-50">
                        {{ $user->rooms }} @if ($user->rooms != 1) Rooms @else Room @endif
                    </p>
                </div>
                <div class="mb-2 col-span-full">
                    <x-livewire.label>
                        <x-slot name='svgicon'>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </x-slot>
                        Budget
                    </x-livewire.label>
                    <div class="flex py-2 pl-3 pr-4 text-base font-medium text-blue-800 border-l-4 border-blue-400 justify-evenly bg-blue-50">
                        <div class="mr-4">
                            <label for="min_price" class="text-sm">Minimum</label>
                            <p class="text-lg">
                                ₦&nbsp;{{ number_format($user->min_budget) }}
                            </p>
                        </div>
                        <div>
                            <label for="max_price" class="text-sm">Maximum</label>
                            <p class="text-lg">
                                ₦&nbsp;{{ number_format($user->max_budget) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endcan     
        </div>
    </div>
    <p class="text-xs text-center text-gray-500 lg:text-left">
        &copy; 2020 Roomee. All rights reserved
    </p>
</div>