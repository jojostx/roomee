<div class="w-full pt-6 pb-6">
    <div class="w-11/12 m-auto">
        <div class="md:grid md:grid-cols-7">
            <div class="flex items-center justify-between mb-5 sm:w-64 md:72 col-span-full lg:fixed sm:block">
                <div class="mb-3 text-2xl md:mb-6">
                    <p class="mb-1 font-semibold">Account Settings</p>
                    <p class="text-base font-semibold text-secondary-700">Hello, {{ auth()->user()->full_name }}.</p>
                </div>

                <ul x-data class="flex-col hidden w-full px-2 py-2 my-2 font-semibold list-none bg-white border rounded-md shadow-sm text-secondary-800 lg:flex">
                    <li class="relative w-full my-1 cursor-pointer">
                        <input type="radio" name="section-link" id="general" class="absolute opacity-0 left-20" autocomplete="off">
                        <label @click="document.getElementById('general-information').scrollIntoView()" for="general" class="flex items-center px-2 py-2 border border-transparent rounded-md cursor-pointer hover:bg-secondary-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-5 mr-3" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            General Information
                        </label>
                    </li>
                    <li class="relative w-full my-1 cursor-pointer">
                        <input type="radio" name="section-link" id="personal" class="absolute opacity-0 left-20" autocomplete="off">
                        <label @click="document.getElementById('personal-information').scrollIntoView()" for="personal" class="flex items-center px-2 py-2 border border-transparent rounded-md cursor-pointer hover:bg-secondary-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-5 mr-3" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Personal Information
                        </label>
                    </li>
                </ul>
            </div>
            <div class="lg:h-4 md:mr-4 col-span-full lg:col-span-2">
            </div>

            <div class="max-w-3xl pb-6 sm:grid-cols-2 col-span-full lg:col-span-5 lg:mt-12">
                <form wire:submit.prevent="save">
                    <div class="my-8 text-secondary-500 lg:mt-6">
                        {{ $this->form }}
                    </div>

                    <div class="my-8 text-secondary-500 lg:mt-6">
                        <button id="save" type="submit" class="block px-4 py-2 font-semibold leading-4 text-white rounded-md shadow-sm bg-primary-600 lg:text-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Save Changes
                        </button>
                    </div>
                </form>
                <div>
                    <div class="my-8 text-secondary-500 lg:mt-6">
                        <div class="p-6 bg-white border border-gray-300 filament-forms-section-component rounded-xl">
                            <div>
                                <button id="save" type="submit" class="block px-3 py-2 font-semibold leading-4 text-white rounded-md shadow-sm bg-warning-600 hover:bg-warning-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-warning-500">
                                    Deactivate Account
                                </button>
                            </div>
                            <div class="pt-6">
                                <button id="save" type="submit" class="block px-3 py-2 font-semibold leading-4 text-white rounded-md shadow-sm bg-danger-600 hover:bg-danger-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-500">
                                    Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>