<div class="w-11/12 m-auto mt-6 mb-6">
    <div class="md:grid md:grid-cols-7">
        <div class="flex items-center justify-between mb-5 sm:w-64 md:72 col-span-full lg:fixed sm:block">
            <div class="mb-3 text-2xl md:mb-6">
                <p class="mb-1 font-semibold">Update Profile</p>
                <p class="text-sm text-gray-700">Please fill all the basic details to get started.</p>
                <p class="inline-flex px-4 py-1 text-sm text-orange-700 bg-orange-200 rounded-full">All fields are required</p>
            </div>
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
        <div class="lg:h-4 md:mr-4 col-span-full lg:col-span-2">
        </div>

        <form enctype="multipart/form-data" wire:submit.prevent="save" class="max-w-3xl pb-6 sm:grid-cols-2 col-span-full lg:col-span-5 lg:mt-12">
            <div class="flex flex-col items-center justify-start px-4 py-4 mb-6 rounded-lg sm:flex-row bg-blue-50 sm:py-6">
                <div class="mb-4 sm:w-2/5 sm:mb-0">
                    <div class="flex flex-col items-center justify-center mt-1">
                        <div wire:ignore class="block w-24 h-24 mb-4 overflow-hidden bg-blue-200 rounded-full">
                            @if (auth()->user()->avatar)
                            <img id="avatar_img" src="{{ auth()->user()->avatarPath }}" alt="avatar image" class="h-full" width="100%" height="100%">
                            @else
                            <img id="avatar_img" src="" alt="avatar image" class="hidden h-full">
                            @endif
                            <svg id="pl-ava" class="w-full h-full text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <label wire:ignore tabindex="0" for="avatar_photo" class="flex items-center justify-between px-3 py-2 text-sm font-semibold leading-4 text-white bg-indigo-600 rounded-md shadow-sm cursor-pointer hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <div class="mr-1.5 loader" id="loader_avatar" style="display: none;">
                            </div>
                            <span>Change</span>
                            <input id="avatar_photo" wire:target="handleAvatarUpload" wire:loading.attr="disabled" name="avatar_photo" type="file" class="sr-only" accept=".jpg, .jpeg, .png" />
                        </label>
                        @error('avatar')
                        <x-livewire.error-text>{{ $message }}</x-livewire.error-text>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col justify-center w-full pt-4 md:pt-2">
                    <div class="flex justify-start gap-4 mb-6">
                        <div class="w-1/2 mr-6">
                            <span class="text-lg font-semibold">{{ ucfirst(auth()->user()->firstname) }}</span>
                            <label class="block text-sm text-gray-700">First Name</label>
                        </div>
                        <div>
                            <span class="text-lg font-semibold">{{ ucfirst(auth()->user()->lastname) }}</span>
                            <label class="block text-sm text-gray-700">Last Name</label>
                        </div>
                    </div>
                    <div class="flex justify-start gap-4">
                        <div class="w-1/2 mr-6 overflow-hidden">
                            <span class="text-lg font-semibold">{{ ucfirst(auth()->user()->email) }}</span>
                            <label class="block text-sm text-gray-700">Email Address</label>
                        </div>
                        <div>
                            <span class="text-lg font-semibold">{{ ucfirst(auth()->user()->gender) }}</span>
                            <label class="block text-sm text-gray-700">Gender</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-2 gap-x-6 lg:gap-y-2 sm:grid-cols-2">
                <div>
                    <label for="cover_photo" class="label">Cover photo
                        <x-required-field-star />
                    </label>
                    <div id="cover_inp" class="overflow-hidden relative flex items-center justify-center px-4 pt-4 pb-4 mt-1 border-2 border-gray-300 @error('cover_photo') border-red-300 @enderror border-dashed rounded-md">
                        <div class="relative w-full space-y-1 text-center">
                            @if (auth()->user()->cover_photo)
                            <svg wire:ignore id="cover-svg" class="hidden w-12 h-12 mx-auto text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div wire:ignore>
                                <img src="{{ auth()->user()->coverPhotoPath }}" id="cover_out" class="z-10 block w-full mb-5 rounded-lg shadow-lg" alt="" height="100%" width="100%">
                            </div>
                            @else
                            <svg wire:ignore id="cover-svg" class="w-12 h-12 mx-auto text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div wire:ignore>
                                <img src="" id="cover_out" class="z-10 hidden block w-full mb-5 rounded-lg shadow-lg" alt="">
                            </div>
                            @endif
                            <div class="flex flex-col items-center mt-6 text-sm text-gray-600">
                                <label tabindex="0" for="cover_photo" class="relative flex items-center justify-between px-3 py-2 mb-2 font-semibold leading-4 text-white bg-indigo-600 rounded-md shadow-sm cursor-pointer hover:bg-indigo-700 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <!-- flex items-center justify-between px-3 py-2 text-sm font-semibold leading-4 text-white bg-indigo-600 rounded-md shadow-sm cursor-pointer hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 -->
                                    <div wire:ignore class="mr-1.5 loader" id="loader_cover" style="display: none;">
                                    </div>
                                    <span>
                                        @if ($cover_photo)
                                        Change cover photo
                                        @else
                                        Upload a cover photo
                                        @endif
                                    </span>
                                    <input wire:ignore id="cover_photo" name="cover_photo" type="file" class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">
                                PNG, JPG, GIF images of up to 5MB
                            </p>
                        </div>
                    </div>
                    @error('cover_photo')
                    <x-livewire.error-text>{{ $message }}</x-livewire.error-text>
                    @enderror
                </div>
            </div>

            <div class="grid pt-6 gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-2 field_set" data-link="li-pers">
                <div class="col-span-full" id="pers_info">
                    <h1 class="text-xl font-semibold">Personal Information</h1>
                </div>
                <div class="col-span-full">
                    <label for="bio" class="label">About
                        <x-required-field-star /> <span class="text-xs text-blue-800">(25-255 characters long)</span>
                    </label>
                    <textarea rows="3" name="bio" class="select_dropdown @error('bio') border-red-500 @enderror" id="bio" wire:model.lazy="bio" placeholder="Write about how you would describe yourself">{{ $bio }}</textarea>
                    @error('bio')
                    <x-livewire.error-text>{{ $message }}</x-livewire.error-text>
                    @enderror
                </div>
                <x-livewire.multiselect :isRequired="true" :name="'hobby'" :options="$hobbies" :selectedOptions="$selectedHobbies" :label="$label = 'Hobbies & Interests'">Hobbies</x-livewire.multiselect>
                <x-livewire.multiselect :isRequired="true" :name="'dislike'" :options="$dislikes" :selectedOptions="$selectedDislikes" :label="$label = 'Dislikes'">Dislikes</x-livewire.multiselect>
            </div>

            <div class="grid pt-6 gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-2 field_set" data-link="li-edu">
                <div class="col-span-full" id="edu_info">
                    <h1 class="text-xl font-semibold">Educational Information</h1>
                </div>
                <div class="mb-2 col-span-full">
                    <label for="institute" class="label">Institute of Study
                        <x-required-field-star />
                    </label>
                    <select wire:change="selectedSchoolChange($event.target.value)" name="school" required autocomplete="off" class="select_dropdown" name="institute" id="institute">
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
                @if ($selectedSchool)
                <div class="col-span-1 mb-2">
                    <label for="course" class="block mb-2 font-medium">Course of Study
                        <x-required-field-star />
                    </label>
                    <select wire:change="selectedCourseChange($event.target.value)" name="course" required autocomplete="off" class="select_dropdown" name="course" id="course">
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
                @endif
                @if ($selectedCourse)
                <div class="col-span-1">
                    <label for="course_level" class="label">Course Level
                        <x-required-field-star />
                    </label>
                    <select wire:change.self.stop="$set('selectedCourseLevel',$event.target.value)" required autocomplete="off" name="course_level" class="select_dropdown" name="level" id="course_level">
                        <option value="" disabled selected>Select your course level</option>
                        @foreach ($course_levels as $level)
                        @if ($selectedCourseLevel)
                        <x-livewire.option value="{{ $level }}" :selected="($level === $selectedCourseLevel)?true:false">{{ ($loop->last) ? 'Post-Graduate' : $level }}</x-livewire.option>
                        @else
                        <x-livewire.option value="{{ $level }}">{{ ($loop->last) ? 'Post-Graduate' : $level }}</x-livewire.option>
                        @endif
                        @endforeach
                    </select>
                    @error('course_levels')
                    <x-livewire.error-text>{{ $message }}</x-livewire.error-text>
                    @enderror
                </div>
                @endif
            </div>

            <div class="grid pt-6 mb-4 group gap-x-6 gap-y-2 lg:gap-y-4 sm:grid-cols-2 field_set" data-link="li-apart">
                <div class="col-span-full" id="apart_info">
                    <h1 class="text-xl font-semibold">Apartment Information</h1>
                </div>

                <x-livewire.multiselect :name="'town'" :isRequired="true" :options="$towns" :selectedOptions="$selectedTowns" :label="$label = 'Preferred property locations'">Towns</x-livewire.multiselect>

                <div class="col-span-1 mb-2">
                    <label for="rooms" class="label">Number of Rooms
                        <x-required-field-star />
                    </label>
                    <select wire:model="rooms" required autocomplete="off" class="select_dropdown" name="no of rooms" id="rooms">
                        <option value="" disabled @if (!auth()->user()->rooms) {{ 'selected' }} @endif >Select the number of rooms in the apartment</option>
                        @for ($rooms_ = 1; $rooms_<=5; $rooms_++) <option value="{{ $rooms_ }}" @if (auth()->user()->rooms == $rooms_) selected @endif >
                            @if($rooms_ === 1) Self-contain
                            @elseif ($rooms_ === 5) {{ $rooms_ }} rooms and above
                            @else {{ $rooms_ }}&nbsp;{{ Str::plural('room', $rooms_) }}
                            @endif
                            </option>
                            @endfor
                    </select>
                    @error('rooms')
                    <x-livewire.error-text>{{ $message }}</x-livewire.error-text>
                    @enderror
                </div>

                <div class="col-span-1 mb-2">
                    <label for="min_price" class="label">Minimum Budget
                        <x-required-field-star />
                    </label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">
                                ₦
                            </span>
                        </div>
                        <select wire:model="min_budget" autocomplete="off" required class="pl-6 select_dropdown" name="min_budget" id="min_price">
                            <option value="" disabled @if (!auth()->user()->min_budget) {{ 'selected' }} @endif >Select your minimum budget</option>
                            @foreach ($budgetRange as $price )
                            @if (auth()->user()->min_budget)
                            @if ($price==auth()->user()->min_budget)
                            <option value="{{ $price }}" selected>{{ number_format($price) }}</option>
                            @else
                            <option value="{{ $price }}">{{ number_format($price) }}</option>
                            @endif
                            @else
                            <option value="{{ $price }}">{{ number_format($price) }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    @error('min_budget')
                    <x-livewire.error-text>{{ $message }}</x-livewire.error-text>
                    @enderror
                </div>

                <div class="col-span-1">
                    <label for="max_price" class="label">Maximum Budget
                        <x-required-field-star />
                    </label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">
                                ₦
                            </span>
                        </div>
                        <select wire:model="max_budget" autocomplete="off" required class="pl-6 select_dropdown" name="max_budget" id="max_price">
                            <option value="" disabled @if (!auth()->user()->max_budget) {{ 'selected' }} @endif >Select your maximum budget</option>
                            @foreach ($budgetRange as $price )
                            @if (auth()->user()->max_budget)
                            @if ($price==auth()->user()->max_budget)
                            <option value="{{ $price }}" selected>{{ number_format($price) }}</option>
                            @else
                            <option value="{{ $price }}">{{ number_format($price) }}</option>
                            @endif
                            @else
                            <option value="{{ $price }}">{{ number_format($price) }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    @error('max_budget')
                    <x-livewire.error-text>{{ $message }}</x-livewire.error-text>
                    @enderror
                </div>
            </div>

            @if ($errors->any())
            <div class="section">
                <div class="px-4 py-4 mb-2 text-red-600 bg-red-100 rounded-md">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <div class="my-8 text-gray-500 lg:mt-0">
                <button id="save" type="submit" @if ($errors->any()) disabled @endif class="block w-full px-4 py-3 font-semibold leading-4 text-white bg-indigo-600 rounded-md shadow-sm lg:text-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Changes
                </button>
            </div>

            <div class="px-4 py-4 text-gray-600 bg-gray-100 rounded-md">
                <p class="text-xs lg:text-sm">
                    * Please make sure all the details you have provided are accurate and the photos you have provided are recent photos of you. Uploading offensive or sensitive photos or
                    Bio would lead to your account being blocked and deactivated. For more info read Roomee's <a href="{{ route('terms') }}" class="text-blue-600 hover:underline"> Terms of Use</a>
                </p>
            </div>
        </form>
    </div>
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

    const avatarInput = document.getElementById('avatar_photo');
    avatarInput.addEventListener('change', (e) => {
        const img_ava = document.getElementById('avatar_img');
        let loader = document.querySelector('.loader');

        let options = {
            max_size: 5507566,
            aspectRatio: 1,
            maxHeight: 120,
            maxWidth: 120,
            loader,
            output: img_ava,
            hideableElems: []
        }

        handleFileUpload(e, 'avatar', options)
    })

    const coverInput = document.getElementById('cover_photo');
    coverInput.addEventListener('change', (e) => {
        const cover = document.getElementById('cover_out');
        let loader = document.getElementById('loader_cover');
        let coverSVG = document.getElementById('cover-svg');

        let options = {
            max_size: 4507566,
            aspectRatio: 1.5,
            maxWidth: 500,
            maxHeight: 320,
            loader,
            output: cover,
            hideableElems: [coverSVG]
        }

        handleFileUpload(e, 'cover_photo', options);
    })

    //image manipulation utility functions
    function handleFileUpload(event, livewireProperty, options) {
        let {
            max_size,
            loader,
            aspectRatio,
            maxWidth,
            maxHeight,
            output,
            hideableElems
        } = options;
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

            @this.upload(livewireProperty, b64toBlob(resizedImage), (uploadedFilename) => {
                console.log(uploadedFilename);
            }, () => {
                console.log('unable to upload photo please try again');
            });

            // window.Livewire.emit(livewireProperty, resizedImage);
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

    //dom helper functions
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

    /**
     * Convert a base64 string in a Blob according to the data and contentType.
     * 
     * @param b64Data {String} Pure base64 string without contentType
     * @param contentType {String} the content type of the file i.e (image/jpeg - image/png - text/plain)
     * @param sliceSize {Int} SliceSize to process the byteCharacters
     * @see http://stackoverflow.com/questions/16245767/creating-a-blob-from-a-base64-string-in-javascript
     * @return Blob
     */
    function b64toBlob(b64Data, sliceSize) {
        // Split the base64 string in data and contentType
        var block = b64Data.split(";");
        // Get the content type of the image
        contentType = block[0].split(":")[1] || '';
        // get the real base64 content of the file
        b64Data = block[1].split(",")[1]; // In this case "R0lGODlhPQBEAPeoAJosM...."

        sliceSize = sliceSize || 512;

        var byteCharacters = atob(b64Data);
        var byteArrays = [];

        for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            var slice = byteCharacters.slice(offset, offset + sliceSize);

            var byteNumbers = new Array(slice.length);
            for (var i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }

            var byteArray = new Uint8Array(byteNumbers);

            byteArrays.push(byteArray);
        }

        var blob = new Blob(byteArrays, {
            type: contentType
        });
        
        return blob;
    }
</script>
@endprepend