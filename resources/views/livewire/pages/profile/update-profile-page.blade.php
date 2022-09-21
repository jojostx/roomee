<div class="w-11/12 m-auto mt-6 mb-6">
    <div class="md:grid md:grid-cols-7">
        <div class="flex items-center justify-between mb-5 sm:w-64 md:72 col-span-full lg:fixed sm:block">
            <div class="mb-3 text-2xl md:mb-6">
                <p class="mb-1 font-semibold">Update Profile</p>
                <p class="text-sm text-gray-700">Please fill all the basic details to get started.</p>
                <p class="inline-flex px-4 py-1 text-sm text-orange-700 bg-orange-200 rounded-full">All fields are required</p>
            </div>

            <ul x-data class="flex-col hidden w-full px-2 py-2 my-2 overflow-y-auto font-semibold text-gray-800 list-none bg-gray-100 border rounded-md shadow-sm lg:flex">
                <li class="relative w-full my-1 cursor-pointer">
                    <input type="radio" name="section-link" id="general" class="absolute opacity-0 left-20" autocomplete="off">
                    <label @click="document.getElementById('general-information').scrollIntoView()" for="general" class="flex items-center px-2 py-2 border border-transparent rounded-md cursor-pointer hover:bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-5 mr-3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        General Information
                    </label>
                </li>
                <li class="relative w-full my-1 cursor-pointer">
                    <input type="radio" name="section-link" id="personal" class="absolute opacity-0 left-20" autocomplete="off">
                    <label @click="document.getElementById('personal-information').scrollIntoView()" for="personal" class="flex items-center px-2 py-2 border border-transparent rounded-md cursor-pointer hover:bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-5 mr-3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Personal Information
                    </label>
                </li>
                <li class="relative w-full my-1 cursor-pointer">
                    <input type="radio" name="section-link" id="education" class="absolute opacity-0 left-20" autocomplete="off">
                    <label @click="document.getElementById('educational-information').scrollIntoView()" for="education" class="flex items-center px-2 py-2 border border-transparent rounded-md cursor-pointer hover:bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 mr-3">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                        Educational Information
                    </label>
                </li>
                <li class="relative w-full my-1 cursor-pointer">
                    <input type="radio" name="section-link" id="apartment" class="absolute opacity-0 left-20" autocomplete="off">
                    <label @click="document.getElementById('apartment-information').scrollIntoView()" for="apartment" class="flex items-center px-2 py-2 border border-transparent rounded-md cursor-pointer hover:bg-gray-300">
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
            <div class="my-8 text-gray-500 lg:mt-6">
                {{ $this->form }}
            </div>

            <div class="my-8 text-gray-500 lg:mt-6">
                <button id="save" type="submit" class="block w-full px-4 py-3 font-semibold leading-4 text-white bg-indigo-600 rounded-md shadow-sm lg:text-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Changes
                </button>
            </div>

            <div class="px-4 py-4 text-gray-600 bg-gray-100 rounded-md">
                <p class="text-xs lg:text-sm">
                    * Please make sure all the details you have provided are accurate and the photos you have provided are recent photos of you. Uploading offensive or sensitive photos or
                    Bio would lead to your account being blocked and deactivated. For more info read Roomee's <a href="{{ route('terms') }}" class="text-primary-600 hover:underline"> Terms of Use</a>
                </p>
            </div>
        </form>
    </div>
</div>