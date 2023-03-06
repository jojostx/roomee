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
                        <input type="radio" name="section-link" id="personal-info" class="absolute opacity-0 left-20" autocomplete="off">
                        <label @click="document.getElementById('personal_information').scrollIntoView()" for="personal" class="flex items-center px-2 py-2 border border-transparent rounded-md cursor-pointer hover:bg-secondary-300">
                            <x-heroicon-o-user class="flex-shrink-0 w-5 mr-3" />
                            Personal Information
                        </label>
                    </li>
                    <li class="relative w-full my-1 cursor-pointer">
                        <input type="radio" name="section-link" id="email-and-phone-number-link" class="absolute opacity-0 left-20" autocomplete="off">
                        <label @click="document.getElementById('email_and_phone_number').scrollIntoView()" for="general" class="flex items-center px-2 py-2 border border-transparent rounded-md cursor-pointer hover:bg-secondary-300">
                            <x-heroicon-o-inbox class="flex-shrink-0 w-5 mr-3" />
                            Email and Phone Number
                        </label>
                    </li>
                    <li class="relative w-full my-1 cursor-pointer">
                        <input type="radio" name="section-link" id="password-info-link" class="absolute opacity-0 left-20" autocomplete="off">
                        <label @click="document.getElementById('password_information').scrollIntoView()" for="general" class="flex items-center px-2 py-2 border border-transparent rounded-md cursor-pointer hover:bg-secondary-300">
                            <x-heroicon-o-lock-closed class="flex-shrink-0 w-5 mr-3" />
                            Password Information
                        </label>
                    </li>
                </ul>
            </div>

            <div class="lg:h-4 md:mr-4 col-span-full lg:col-span-2">
            </div>

            <div class="max-w-3xl pb-6 sm:grid-cols-2 col-span-full lg:col-span-5 lg:mt-12">
                <div id="personal_information">
                    <p class="mb-4 font-bold tracking-wide sm:text-lg">Personal Information</p>

                    <div class="p-4 bg-white border rounded-lg shadow-sm md:p-6">
                        <form wire:submit.prevent="savePersonalInfo">
                            {{ $this->personalInfoForm }}

                            <div class="flex items-center mt-6">
                                <x-filament::button type="submit" id="save-personal-info" size='sm' style="font-weight: 600;">
                                    {{ __('Update') }}
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-6 sm:mt-10" id="email_and_phone_number">
                    <p class="mb-4 font-bold tracking-wide sm:text-lg">Email and Phone number</p>

                    <div class="p-4 bg-white border rounded-lg shadow-sm md:p-6">
                        <form wire:submit.prevent="saveContactInfo">
                            {{ $this->contactInfoForm }}

                            <div class="flex items-center mt-6">
                                <x-filament::button type="submit" id="save-contact-info" size='sm' style="font-weight: 600;">
                                    {{ __('Update') }}
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-6 sm:mt-10" id="password_information">
                    <p class="mb-4 font-bold tracking-wide sm:text-lg">Password Information</p>

                    <div class="p-4 bg-white border rounded-lg shadow-sm md:p-6">
                        <form wire:submit.prevent="savePasswordInfo">
                            {{ $this->passwordInfoForm}}

                            <div class="mt-4">
                                <div class="font-medium">Password requirements:</div>
                                <div class="text-sm text-gray-500">Ensure that these requirements are met:</div>
                                <ul class="pl-4 text-xs leading-4 text-gray-500">
                                    <li class="">1. At least 10 characters (and up to 100 characters)</li>
                                    <li class="">2. At least one lowercase and one uppercase character</li>
                                    <li class="">32. Inclusion of at least one special character, e.g., ! @ # ?</li>
                                </ul>
                            </div>

                            <div class="flex items-center mt-6">
                                <x-filament::button type="submit" id="save-password-info" size='sm' style="font-weight: 600;">
                                    {{ __('Update') }}
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-6 sm:mt-10">
                    <div class="my-8 space-y-4 text-secondary-600 lg:mt-6">
                        <div>
                            <h2 class="text-lg font-bold tracking-wide sm:text-xl text-danger-600">Danger Zone</h2>
                        </div>
                        <div class="bg-white border divide-y border-danger-400 filament-forms-section-component rounded-xl">
                            <div class="p-4 space-y-2">
                                <h1 class="font-semibold text-secondary-800">Deactivate Account</h1>
                                <p class="text-sm">
                                    Deactivating your account will prevent other users from viewing your profile, interacting with you by sending you a Roommate request, and other actions, your profile will also not be recommended to other users.
                                    If you deactivate your {{ config("app.name") }} account, you may always reactivate it later.
                                </p>

                                <x-filament::button id="deactivate-account" outlined="true" type="button" size='md' color="danger">
                                    {{ __('Deactivate your account') }}
                                </x-filament::button>
                            </div>
                            <div class="p-4 space-y-2">
                                <h1 class="font-semibold text-secondary-800">Delete Account</h1>
                                <p class="text-sm">
                                    If you delete your {{ config("app.name") }} account, your account will be permanently removed from {{ config("app.name") }} including your photos, profile etc.
                                    Once you delete your account, there is no going back. Please be certain.
                                </p>

                                <x-filament::button id="delete-account" outlined="true" type="button" size='md' color="danger">
                                    {{ __('Delete your account') }}
                                </x-filament::button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
