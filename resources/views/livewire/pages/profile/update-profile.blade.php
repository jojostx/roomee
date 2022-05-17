<div class="w-11/12 m-auto mt-6 mb-6">
    <div class="md:grid md:grid-cols-7">
        <div class="flex items-center justify-between mb-5 sm:w-64 md:72 col-span-full lg:fixed sm:block">
            <div class="mb-3 text-2xl md:mb-6">
                <p class="mb-1 font-semibold">Update Profile</p>
                <p class="text-sm text-gray-700">Please fill all the basic details to get started.</p>
                <p class="inline-flex px-4 py-1 text-sm text-orange-700 bg-orange-200 rounded-full">All fields are required</p>
            </div>

            <ul x-data class="flex-col hidden w-full px-2 py-2 my-2 overflow-y-auto font-semibold text-gray-800 list-none bg-gray-100 border rounded-md shadow-sm lg:flex">
                <li class="relative w-full my-1">
                    <input type="radio" name="section-link" id="personal" class="absolute opacity-0 left-20" autocomplete="off">
                    <label @click="document.getElementById('personal-information').scrollIntoView()" for="personal" class="flex items-center px-2 py-2 border border-transparent rounded-md hover:bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-5 mr-3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Personal Information
                    </label>
                </li>
                <li class="relative w-full my-1">
                    <input type="radio" name="section-link" id="education" class="absolute opacity-0 left-20" autocomplete="off">
                    <label @click="document.getElementById('educational-information').scrollIntoView()" for="education" class="flex items-center px-2 py-2 border border-transparent rounded-md hover:bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 mr-3">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                        Educational Information
                    </label>
                </li>
                <li class="relative w-full my-1">
                    <input type="radio" name="section-link" id="apartment" class="absolute opacity-0 left-20" autocomplete="off">
                    <label @click="document.getElementById('apartment-information').scrollIntoView()" for="apartment" class="flex items-center px-2 py-2 border border-transparent rounded-md hover:bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-5 mr-3" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Apartment Information
                    </label>
                </li>
            </ul>
        </div>
        <div class="lg:h-4 md:mr-4 col-span-full lg:col-span-2">
        </div>

        <form wire:submit.prevent="save" class="max-w-3xl pb-6 sm:grid-cols-2 col-span-full lg:col-span-5 lg:mt-12">
            <div x-data="photo_upload" class="my-8 text-gray-500 lg:mt-6">
                {{ $this->form }}
            </div>

            <div class="my-8 text-gray-500 lg:mt-6">
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

@push('scripts')    
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('photo_upload', () => ({
            avatar: {
                ['@change']($event) {
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

                    this.handleFileUpload($event, 'avatar', options)
                }
            },

            cover: {
                ['@change']($event) {
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

                    this.handleFileUpload($event, 'cover_photo', options);
                }
            },

            //image manipulation utility functions
            handleFileUpload(event, livewireProperty, options) {
                let {
                    max_size,
                    loader,
                    aspectRatio,
                    maxWidth,
                    maxHeight,
                    output,
                    hideableElems
                } = options;

                const image = event.target.files[0];
                const IMG_TYPES = ['image/jpg', 'image/png', 'image/jpeg'];
                const MAX_FILE_SIZE = max_size ? max_size : 5242880;

                //check file type
                if (image.type && !IMG_TYPES.includes(image.type)) {
                    this.$dispatch('open-alert', {
                        alert_type: 'danger',
                        message: 'only images of MIME types: JPG, JPEG and PNG are allowed'
                    });

                    return
                }

                //check file size for validity
                if (image.size > MAX_FILE_SIZE) {
                    this.$dispatch('open-alert', {
                        alert_type: 'danger',
                        message: `only images less than ${ (MAX_FILE_SIZE/1000000).toFixed(1) }MB are allowed`
                    });

                    return
                }

                let reader = new FileReader();

                reader.onloadstart = () => {
                    if (loader) {
                        loader.style.display = 'flex';
                    }
                }

                reader.onloadend = async () => {
                    const result = await this.cropImage(reader.result, aspectRatio);
                    const resizedImage = await this.resizeImage(result, maxWidth, maxHeight);

                    $wire.upload(
                        livewireProperty,
                        this.b64toBlob(resizedImage),
                        finishCallback = (uploadedFilename) => {
                            this.hideElements(loader, ...hideableElems);
                            this.displayImage(output, resizedImage)
                        },
                        errorCallback = () => {
                            this.$dispatch('open-alert', {
                                alert_type: 'danger',
                                message: 'unable to upload photo please try again'
                            })
                        },
                    )
                }

                reader.readAsDataURL(image);
            },

            resizeImage(base64Str, maxWidth = 300, maxHeight = 250) {
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
            },

            cropImage(base64Str, aspectRatio = 1) {
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
            },

            //dom helper functions
            hideElements(...elements) {
                if (!elements && !(elements instanceof Array)) {
                    return
                }

                elements.forEach(element => {
                    element.style.display = 'none';
                });
            },

            displayImage(output, image) {
                if (output && image) {
                    output.classList.remove('hidden');
                    output.src = `${image}`
                    return
                }
            },

            b64toBlob(b64Data, sliceSize) {
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
        }))
    })
</script>
@endpush