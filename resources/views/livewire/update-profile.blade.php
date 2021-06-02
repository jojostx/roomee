<div class="w-11/12 m-auto my-5 mb-6">
    <div class="md:grid md:grid-cols-7 md:gap-6">
        <div class="w-64 lg:fixed">
            <p class="mb-4 text-2xl font-semibold">Update Profile</p>
            <ul wire:ignore class="flex-col hidden w-full px-2 py-2 my-2 overflow-y-auto font-semibold text-gray-800 list-none bg-gray-100 border rounded-md shadow-sm lg:flex">
                <li class="w-full my-1">
                    <a href="#pers_info" class="flex items-center px-2 py-2 rounded-md profile_links hover:bg-gray-300 active">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-5 mr-3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Personal Information
                    </a>
                </li>
                <li class="w-full my-1">
                    <a href="#edu_info" class="flex items-center px-2 py-2 rounded-md profile_links hover:bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 mr-3">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                        Educational Information
                    </a>
                </li>
                <li class="w-full my-1">
                    <a href="#apart_info" class="flex items-center px-2 py-2 rounded-md profile_links hover:bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-5 mr-3" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Apartment Information
                    </a>
                </li>
            </ul>
        </div>
        <div class="lg:h-4 md:mr-4 col-span-full lg:col-span-2">
        </div>
        <form method="POST" enctype="multipart/form-data" wire:submit.prevent="save" class="max-w-2xl pb-6 md:px-6 sm:grid-cols-2 col-span-full lg:col-span-5 lg:mt-12">
            <div class="px-4 py-4 mb-6 rounded-lg bg-blue-50 sm:py-6 sm:grid gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-3">
                <div class="mb-4 col-span-full sm:col-span-1 sm:mb-0">
                    <div class="flex flex-col items-center justify-center mt-1">


                        <div wire:ignore class="block w-24 h-24 mb-4 overflow-hidden bg-blue-200 rounded-full">
                            <img id="avatar_img" src="" alt="avatar image" class="hidden h-full">
                            <svg id="pl-ava" class="w-full h-full text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <!-- <label tabindex="0" for="profile_photo" class="flex items-center justify-between px-3 py-2 text-sm font-semibold leading-4 text-white bg-indigo-600 rounded-md shadow-sm cursor-pointer hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <div wire:loading.flex wire:target="avatar" class="mr-1.5 loader">
                            </div>
                            <span>Change</span>
                            <input id="profile_photo" name="profile_photo" wire:model="avatar" wire:target="avatar" wire:loading.attr="disabled" type="file" class="sr-only" accept=".jpg, .jpeg, .png"/>
                        </label> -->

                        <label wire:ignore tabindex="0" for="profile_photo" class="flex items-center justify-between px-3 py-2 text-sm font-semibold leading-4 text-white bg-indigo-600 rounded-md shadow-sm cursor-pointer hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <div class="mr-1.5 loader" style="display: none;">
                            </div>
                            <span>Change</span>
                            <input id="profile_photo" wire:target="handleAvatarUpload" wire:loading.attr="disabled" name="profile_photo" type="file" class="sr-only" accept=".jpg, .jpeg, .png" />
                        </label>
                        @error('avatar')
                        <span class="text-xs text-red-500 ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="inline w-3 mb-0.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col justify-center col-span-2 pt-4 md:pt-2">
                    <div class="flex justify-start mb-6">
                        <div class="w-1/2 mr-6">
                            <label class="block text-sm text-gray-700">First Name</label>
                            <span class="text-lg font-semibold">{{ ucfirst(auth()->user()->firstname) }}</span>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Last Name</label>
                            <span class="text-lg font-semibold">{{ ucfirst(auth()->user()->lastname) }}</span>
                        </div>
                    </div>
                    <div class="flex justify-start">
                        <div class="w-1/2 mr-6">
                            <label class="block text-sm text-gray-700">Email Address</label>
                            <span class="text-lg font-semibold">{{ ucfirst(auth()->user()->email) }}</span>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Gender</label>
                            <span class="text-lg font-semibold">{{ ucfirst(auth()->user()->gender) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-2">
                <div wire:ignore>
                    <label for="cover_photo" class="block mb-2 font-medium text-gray-700">Cover photo</label>
                    <div id="cover_inp" class="flex justify-center px-4 pt-4 pb-4 mt-1 mb-4 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="relative w-full space-y-1 text-center">
                            <svg id="cover-svg" class="w-12 h-12 mx-auto text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div>
                                <img src="" id="cover_out" class="z-10 hidden block w-full mb-5 rounded-lg shadow-lg" alt="">
                            </div>
                            <div class="flex flex-col items-center mt-6 text-sm text-gray-600">
                                <label for="cover_photo" class="relative flex items-center justify-between px-3 py-2 mb-2 font-medium text-indigo-600 bg-blue-100 rounded-md cursor-pointer hover:text-indigo-500 hover:shadow-sm focus-within:outline-none focus:border-indigo-400 focus-within:ring focus-within:ring-offset-2 focus-within:ring-indigo-200 focus:ring-opacity-50">
                                    <div class="mr-1.5 loader-dark" style="display: none;">
                                    </div>
                                    <span>Upload a cover photo</span>
                                    <input id="cover_photo" name="cover_photo" type="file" class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">
                                PNG, JPG, GIF images of up to 5MB
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid pt-6 gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-2">
                <div class="col-span-full" id="pers_info">
                    <h1 class="text-xl font-semibold">Personal Information</h1>
                </div>
                <div class="col-span-full">
                    <label for="bio" class="block mb-2 font-medium text-gray-700">Bio</label>
                    <textarea rows="3" name="bio" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('bio') border-red-500 @enderror" id="bio" wire:model.lazy="bio" placeholder="Write something about yourself">{{ $bio }}</textarea>
                    @error('bio')
                    <span class="text-xs text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="inline w-3 mb-0.5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <x-livewire.select2 :name="$hobby = 'hobby'" :options="$hobbies" :selectedOptions="$selectedHobbies">Hobbies</x-livewire.select2>
                <x-livewire.select2 :name="$dislike = 'dislike'" :options="$dislikes" :selectedOptions="$selectedDislikes">Dislikes</x-livewire.select2>
            </div>
            <div class="grid pt-6 gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-2">
                <div class="col-span-full" id="edu_info">
                    <h1 class="text-xl font-semibold">Educational Information</h1>
                </div>
                <div class="mb-2 col-span-full">
                    <label for="institute" class="block mb-2 font-medium text-gray-700">Institute of Study</label>
                    <select wire:change="selectedSchoolChange($event.target.value)" name="school" required autocomplete="off" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="institute" id="institute">
                        <option value="" disabled @if (!$selectedSchool) {{ 'selected' }} @endif>Select your institute of study</option>
                        @foreach ($schools as $school)
                        @if ($selectedSchool)
                        <x-livewire.option value="{{ $school->id }}" :selected="($school->id == $selectedSchool->id)?true:false"> {{ $school['name'] }}</x-livewire.option>
                        @else
                        <x-livewire.option value="{{ $school->id }}"> {{ $school['name'] }}</x-livewire.option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-span-1 mb-2">
                    <label for="course" class="block mb-2 font-medium">Course of Study</label>
                    <select wire:change="selectedCourseChange($event.target.value)" name="course" required autocomplete="off" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="course" id="course">
                        <option value="" disabled @if (!$selectedCourse) {{ 'selected' }} @endif>Select your course of study</option>
                        @foreach ($courses as $course)
                        @if ($selectedCourse)
                        <x-livewire.option value="{{ $course->id }}" :selected="($course->id == $selectedCourse->id)?true:false"> {{ $course['name'] }}</x-livewire.option>
                        @else
                        <x-livewire.option value="{{ $course->id }}"> {{ $course['name'] }}</x-livewire.option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-span-1">
                    <label for="course_level" class="block mb-2 font-medium text-gray-700">Course Level</label>
                    <select wire:change.self.stop="$set('selectedCourseLevel',$event.target.value)" required autocomplete="off" name="course_level" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="level" id="course_level">
                        <option value="" disabled selected>Select your course level</option>
                        @foreach ($course_levels as $level)
                        @if ($selectedCourseLevel)
                        <x-livewire.option value="{{ $level }}" :selected="($level === $selectedCourseLevel)?true:false">{{ ($loop->last) ? 'Post-Graduate' : $level }}</x-livewire.option>
                        @else
                        <x-livewire.option value="{{ $level }}">{{ ($loop->last) ? 'Post-Graduate' : $level }}</x-livewire.option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid pt-6 mb-4 group gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-2">
                <div class="col-span-full" id="apart_info">
                    <h1 class="text-xl font-semibold">Apartment Information</h1>
                </div>
                <div class="col-span-1 mb-2">
                    <label for="prop_location" class="block mb-2 font-medium text-gray-700">Preferred Property Location</label>
                    <select wire:change="selectedTownsChange($event.target.value)" autocomplete="off" name="property_location" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="property locations" id="prop_location">
                        <option value="" disabled @if ($selectedTowns->isEmpty()) {{ 'selected' }} @endif>Select your preferred property location</option>
                        @if ($towns)
                            @foreach ($towns as $town)
                                @if ($selectedTowns->contains($town->id))
                                    <option value="{{ $town['id'] }}" selected> {{ $town['name'] }} </option>
                                @else
                                    <x-livewire.option value="{{ $town['id'] }}"> {{ $town['name'] }}</x-livewire.option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-span-1 mb-2">
                    <label for="rooms" class="block mb-2 font-medium text-gray-700">Number of Rooms</label>
                    <select wire:model="rooms" required autocomplete="off" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="no of rooms" id="rooms">
                        <option value="" disabled selected>Select the number of rooms in the apartment</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4 and above</option>
                    </select>
                    @error('rooms')
                    <span class="text-xs text-red-500 ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="inline w-3 mb-0.5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="col-span-1 mb-2">
                    <label for="min_price" class="block mb-2 font-medium text-gray-700">Minimum Budget</label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">
                                ₦
                            </span>
                        </div>
                        <select wire:model="min_budget" autocomplete="off" required class="w-full pl-6 text-gray-600 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="min_budget" id="min_price">
                            <option value="" disabled @if (!auth()->user()->min_budget) {{ 'selected' }} @endif >Select your minimum budget</option>
                            @foreach ($budgetRange as $price )
                            @if (auth()->user()->min_budget)
                            @if ($price==auth()->user()->min_budget)
                            <option value="{{ $price }}" selected>{{ $price }}</option>
                            @else
                            <option value="{{ $price }}">{{ $price }}</option>
                            @endif
                            @else
                            <option value="{{ $price }}">{{ $price }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    @error('min_budget')
                    <span class="text-xs text-red-500 ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="inline w-3 mb-0.5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <!-- The minimum budget must be less than the maximum budget -->
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="col-span-1">
                    <label for="max_price" class="block mb-2 font-medium text-gray-700">Maximum Budget</label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">
                                ₦
                            </span>
                        </div>
                        <select wire:model="max_budget" autocomplete="off" required class="w-full pl-6 text-gray-600 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="max_budget" id="max_price">
                            <option value="" disabled @if (!auth()->user()->max_budget) {{ 'selected' }} @endif >Select your maximum budget</option>
                            @foreach ($budgetRange as $price )
                            @if (auth()->user()->max_budget)
                            @if ($price==auth()->user()->max_budget)
                            <option value="{{ $price }}" selected>{{ $price }}</option>
                            @else
                            <option value="{{ $price }}">{{ $price }}</option>
                            @endif
                            @else
                            <option value="{{ $price }}">{{ $price }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    @error('max_budget')
                    <span class="text-xs text-red-500 ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="inline w-3 mb-0.5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ $message }}
                    </span>
                    @enderror
                </div>
            </div>

            <div class="my-8 text-gray-500 lg:mt-0">
                <button id="save" type="submit" class="block w-full px-4 py-3 font-semibold leading-4 text-white bg-indigo-600 rounded-md shadow-sm lg:text-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Changes
                </button>
            </div>

            <div class="px-4 py-4 text-gray-500 bg-gray-100 rounded-md">
                <p class="text-xs lg:text-sm">
                    * Please make sure all the details you have provided are accurate and the photos you have choosecover_photo are recent photos of you. Uploading offensive or sensitive photos or
                    Bio would lead to your account being blocked and deactivated. For more info read Roomee's <a href="{{ route('terms') }}" class="text-blue-600 hover:underline"> Terms of Use</a>
                </p>
            </div>
        </form>
    </div>
    <p class="text-xs text-center text-gray-400 lg:text-left">
        &copy; 2020 Roomee. All rights reserved
    </p>
</div>

@prepend('scripts')
<script>
    function switchActive() {
        const links = document.getElementsByClassName('profile_links');

        for (const link of links) {
            link.addEventListener('click', (e) => {
                for (const a of links) {
                    a.classList.remove('active');
                }
                link.classList.add('active');
            })
        }
    };

    switchActive();


    const avatarInput = document.getElementById('profile_photo');
    avatarInput.addEventListener('change', (e) => {
        const img_ava = document.getElementById('avatar_img');
        let loader = document.querySelector('.loader');

        let options = {
            max_size: 5507566,
            aspectRatio: 1,
            maxHeight: 240,
            maxWidth: 240,
            loader,
            output: img_ava,
            hideableElems: []
        }
        
        handleFileUpload(e, 'avatarUpload', options)
    })

    const coverInput = document.getElementById('cover_photo');
    coverInput.addEventListener('change', (e) => {
        const cover = document.getElementById('cover_out');
        let loader = document.querySelector('.loader-dark');
        let coverSVG = document.getElementById('cover-svg');
        
        let options = {
            max_size: 4507566,
            aspectRatio: 3 / 2,
            maxWidth: 500,
            maxHeight: 320,
            loader,
            output: cover,
            hideableElems: [coverSVG]
        }

        handleFileUpload(e, 'coverUpload', options);
    })

    /* 
    options = {
        max_size : integer (Kilobyte),
        aspect_Ratio : integer,
        maxWidth : integer,
        maxHeight : integer,
        loader ?: HTMLElement,
        outputElement ?: ImageElement,
        hideableElems ?: Array<HTMLElements>[]
    }

     max_size = 4096000, aspectRatio, loader, output, ...args

    */

    //image manipulation utility functions
    function handleFileUpload(event, livewireEventName, options) {
        let {max_size, loader, aspectRatio, maxWidth, maxHeight, output, hideableElems} = options;
        const avatar = event.target.files[0];
        const IMG_TYPES = ['image/jpg', 'image/png', 'image/jpeg'];
        const MAX_FILE_SIZE = max_size ? max_size : 5242880;

        //check file type
        if (avatar.type && !IMG_TYPES.includes(avatar.type)) {
            console.log('only images of MIME types: JPG, JPEG and PNG are allowed');
            return
        }

       

        //check file size for validity
        if (avatar.size > MAX_FILE_SIZE) {
            console.log(`only images less than ${ (MAX_FILE_SIZE/1000000).toFixed(1) }MB are allowed`);
            return
        }

        let reader = new FileReader();

        reader.onloadstart = () => {
            if (loader) {
                loader.style.display = 'flex';
            }
        }

        reader.onloadend = async () => {
            const result = await cropImage(reader.result, aspectRatio);
            const resizedImage = await resizeImage(result, maxWidth, maxHeight);

            displayImage(output, resizedImage);
            hideElements(loader, ...hideableElems);

            window.Livewire.emit(livewireEventName, resizedImage);
        }

        reader.readAsDataURL(avatar);
    }

    function resizeImage(base64Str, maxWidth = 300, maxHeight = 250) {
        return new Promise((resolve) => {
            let img = new Image();
            img.src = base64Str;

            img.onload = () => {
                let canvas = document.createElement('canvas')
                const MAX_WIDTH = maxWidth
                const MAX_HEIGHT = maxHeight
                let width = img.width
                let height = img.height

                if (width > height) {
                    if (width > MAX_WIDTH) {
                        height *= MAX_WIDTH / width
                        width = MAX_WIDTH
                    }
                } else {
                    if (height > MAX_HEIGHT) {
                        width *= MAX_HEIGHT / height
                        height = MAX_HEIGHT
                    }
                }
                canvas.width = width
                canvas.height = height
                let ctx = canvas.getContext('2d')
                ctx.drawImage(img, 0, 0, width, height)
                resolve(canvas.toDataURL())
            }
        })
    }

    function cropImage(base64Str, aspectRatio = 1) {
        return new Promise((resolve) => {
            let img = new Image();
            img.src = base64Str;

            img.onload = () => {
                // let's store the width and height of our image
                const inputWidth = img.naturalWidth;
                const inputHeight = img.naturalHeight;

                // get the aspect ratio of the input image
                const imgAspectRatio = inputWidth / inputHeight;

                // if it's bigger than our target aspect ratio
                let outputWidth = inputWidth;
                let outputHeight = inputHeight;
                if (imgAspectRatio > aspectRatio) {
                    outputWidth = inputHeight * aspectRatio;
                } else if (imgAspectRatio < aspectRatio) {
                    outputHeight = inputWidth / aspectRatio;
                }

                // calculate the position to draw the image at
                const outputX = (outputWidth - inputWidth) * 0.5;
                const outputY = (outputHeight - inputHeight) * 0.5;

                // create a canvas that will present the output image
                const outputImage = document.createElement("canvas");

                // set it to the same size as the image
                outputImage.width = outputWidth;
                outputImage.height = outputHeight;

                // draw our image at position 0, 0 on the canvas
                const ctx = outputImage.getContext("2d");
                ctx.drawImage(img, outputX, outputY);
                resolve(outputImage.toDataURL());
            }
        })
    }

    function hideElements(...elements) {
        if (!elements && !(elements instanceof Array)) {
            return 
        }

        elements.forEach(element => {
            element.style.display = 'none';
        });
    }

    function displayImage(output, image) {
        if (output && image) {
            output.classList.remove('hidden');
            output.src = `${image}`
            return
        }
    }

    class Dropdown {
        constructor(labels, checkboxes) {
            this.labels = labels;
            this.checkboxes = checkboxes;
        }

        addLabelListeners() {
            for (let label of this.labels) {
                label.addEventListener('click', (e) => {
                    e.stopPropagation();
                    label.parentElement.classList.add('hidden');
                })
            }
        }

        addCheckboxListeners() {
            for (let dislike of this.checkboxes) {
                dislike.addEventListener('change', (e) => {
                    e.stopPropagation();
                    if (dislike.checked) {
                        dislike.labels[1].parentElement.classList.add('inline-flex');
                        dislike.labels[1].parentElement.classList.remove('hidden');
                    } else {
                        dislike.labels[1].parentElement.classList.add('hidden');
                        dislike.labels[1].parentElement.classList.remove('inline-flex');
                    }
                })
            }
        }

        addDOMListener() {
            window.addEventListener('DOMContentLoaded', () => {
                for (let option of this.checkboxes) {
                    if (option.checked) {
                        option.labels[1].parentElement.classList.add('inline-flex');
                        option.labels[1].parentElement.classList.remove('hidden');
                    }
                }
            })
        }
    }

</script>
@endprepend