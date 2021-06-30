<div class="w-11/12 m-auto mt-6 mb-6">
    <style>
        [type="radio"][name="tabs"]:checked~label {
            color: rgba(29, 78, 216);
            border-bottom: 2px solid rgba(29, 78, 216);
        }
    </style>
    <div class="flex justify-center w-full md:mt-12">
        <div class="w-full max-w-3xl pb-6">
            <div class="relative flex flex-col-reverse items-center justify-start px-4 py-4 mb-6 border rounded-lg sm:flex-row">
                <div class="relative flex flex-col items-center justify-start w-full md:w-1/2 md:mr-2">
                    <div class="absolute z-20 sm:static -top-14 md:top-0 sm:w-2/5 sm:mb-0">
                        <div class="flex flex-col items-center justify-center mt-1">
                            <div class="block w-24 h-24 mb-2 overflow-hidden bg-blue-200 border-4 border-white rounded-full">
                                @if ($user->avatar)
                                <img id="avatar_img" src="{{ $user->avatarPath }}" alt="avatar image" width="100%" height="100%" class="h-full">
                                @else
                                <svg id="pl-ava" class="w-full h-full text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center w-full mt-14 sm:mt-0 md:pt-2">
                        <div class="flex items-center justify-center mb-4 overflow-hidden">
                            <span class="font-semibold sm:text-lg">{{ ucfirst($user->firstname) }} {{ ucfirst($user->lastname) }}</span>
                            &nbsp;
                            <span>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" stroke="currentColor" fill="none" xmlns="http://www.w3.org/2000/svg">
                                @if ($user->gender == "male")
                                    <path d="M0 2.47475C0 1.10798 1.13964 0 2.54545 0C3.95127 0 5.09091 1.10798 5.09091 2.47475C5.09091 3.84151 3.95127 4.94949 2.54545 4.94949C1.13964 4.94949 0 3.84151 0 2.47475Z" transform="translate(9.45459 0.86364746)" fill="currentColor" fill-rule="evenodd" stroke="none" />
                                    <path d="M4 0L8 0L12 17L0 17L4 0Z" transform="matrix(1 0 0 -1 6.000122 22.813293)" fill="currentColor" fill-rule="evenodd" stroke="none" />
                                @else
                                    <path d="M0 2.47475C0 1.10798 1.13964 0 2.54545 0C3.95127 0 5.09091 1.10798 5.09091 2.47475C5.09091 3.84151 3.95127 4.94949 2.54545 4.94949C1.13964 4.94949 0 3.84151 0 2.47475Z" transform="translate(9.45459 0.86364746)" fill="currentColor" fill-rule="evenodd" stroke="none" />
                                    <path d="M2.00012 0L12.0001 0L8.90909 8.66162L14 17.3232L0 17.3232L5.09091 8.66162L2.00012 0Z" transform="translate(5 5.8131714)" fill="currentColor" fill-rule="evenodd" stroke="none" />
                                @endif
                                </svg>
                            </span>
                        </div>
                        <div class="flex justify-center w-full">
                            @can('update', $user)
                            <a href="{{ route('profile.update')}}" style="border-width: 1.5px;" class="flex items-center justify-start px-2 py-1 text-xs text-blue-700 transition duration-150 ease-in-out border border-blue-700 rounded-md sm:text-sm hover:text-blue-700 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 focus:text-blue-700">
                                <span class="pr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                </span>
                                Edit Profile
                            </a>
                            @elsecannot('update', $user)
                            <button style="border-width: 1.5px;" class="flex items-center px-2 py-1 mr-2 text-xs text-blue-700 transition duration-150 ease-in-out border border-blue-700 rounded-md xs:text-sm hover:text-blue-700 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 focus:text-blue-700">
                                <span class="pr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-4 h-4" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </span>
                                Add to Favorites
                            </button>
                            <button style="border-width: 1.5px;" class="flex items-center justify-start px-2 py-1 text-xs text-blue-700 transition duration-150 ease-in-out border border-blue-700 rounded-md sm:text-sm hover:text-blue-700 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 focus:text-blue-700">
                                <span class="pr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-4 h-4" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </span>
                                Send Request
                            </button>
                            @endcan
                        </div>                        
                    </div>
                </div>
                <div class="relative flex items-center justify-center w-full mt-1 overflow-hidden md:w-1/2">
                    @if ($user->cover_photo)
                    <div wire:ignore>
                        <img src="{{ $user->coverPhotoPath }}" id="cover_out" width="100%" height="100%" class="z-10 block w-full rounded-lg shadow-lg" alt="Your cover photo">
                    </div>
                    @else
                    <svg wire:ignore id="cover-svg" class="w-12 h-12 mx-auto text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @endif
                </div>
            </div>
            @can('view', $user)
            <div wire:ignore class="w-full mb-6 overflow-hidden border rounded-lg" x-data="tabs()">
                <div class="relative flex items-center justify-start py-2 border">
                    <ul class="flex w-full justify-evenly">
                        <li>
                            <input type="radio" autocomplete="off" checked name="tabs" id="info-pers" class="hidden">
                            <label for="info-pers" x-on:click="toggleTabs($refs.tab_pers)" class="flex items-center py-1 text-sm rounded-md info-links hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-5 mr-1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Personal
                            </label>
                        </li>
                        <li>
                            <input type="radio" autocomplete="off" name="tabs" id="info-edu" class="hidden">
                            <label for="info-edu" x-on:click="toggleTabs($refs.tab_edu)" class="flex items-center py-1 text-sm rounded-md info-links hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                </svg>
                                Educational
                            </label>
                        </li>
                        <li>
                            <input type="radio" autocomplete="off" name="tabs" id="info-apart" class="hidden">
                            <label for="info-apart" x-on:click="toggleTabs($refs.tab_apart)" class="flex items-center py-1 text-sm rounded-md info-links hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-5 mr-1" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Apartment
                            </label>
                        </li>
                    </ul>
                </div>
                <div class="relative flex items-center justify-start overflow-x-hidden border">
                    <div x-ref="tab_pers" class="grid flex-shrink-0 w-full px-3 py-6 md:px-6 gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-2 info-tab">
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
                    <div x-ref="tab_edu" class="grid flex-shrink-0 hidden w-full px-3 py-6 md:px-6 gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-2 info-tab">
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
                    <div x-ref="tab_apart" class="grid flex-shrink-0 hidden w-full px-3 py-6 md:px-6 group gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-2 info-tab">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                </div>
            </div>
            @endcan
        </div>
    </div>
    <p class="text-xs text-center text-gray-500">
        &copy; 2020 Roomee. All rights reserved
    </p>
    @prepend('scripts')
    <script>
        function tabs() {
            return {
                show: true,
                toggleTabs(tab) {
                    document.querySelectorAll('.info-tab').forEach((tab) => {
                        tab.classList.add('hidden');
                    });

                    tab.classList.remove('hidden');
                },
            }
        }
    </script>
    @endprepend
</div>