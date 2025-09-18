<x-landing-layout>
    <x-slot name="banner">
        <div class="flex items-center justify-center px-4 py-24 text-center bg-primary-100 h-60 md:h-80">
            <p class="text-3xl font-extrabold text-secondary-700 md:text-4xl lg:text-5xl">Frequently Asked Questions</p>
        </div>
    </x-slot>

    <div class="absolute top-0 h-screen hero"></div>

    <!-- FAQs Accordion -->
    <div class="px-4">
        <div class="flex flex-col w-full py-16 mx-auto md:w-4/5">
            <x-accordion :groups="$groups"/>
        </div>
    </div>
    <!-- End Of Accordion -->

    <!-- Feedback Section -->
    <div class="px-4 py-16 bg-secondary-100">
        <div class="flex flex-col items-center w-10/12 mx-auto">
            <p class="mb-4 text-xl font-semibold">Was this article helpful?</p>
            <form x-on:submit.prevent="submitForm('http://127.0.0.1:8000/faqs')" x-data="form('feedback_Form')" method="POST" id="feedback_Form" class="flex flex-col items-center justify-center mb-4">
                @csrf

                <div class="flex mb-4">
                    <input type="radio" x-on:change=" appear = false " name="feedback" value="1" id="positive" class="hidden positive-feedback">
                    <label for="positive" class="w-10 mr-4 text-center positive-feedback-label hover:text-warning-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="font-semibold">Yes</p>
                    </label>
                    <input type="radio" x-on:change=" appear = true " name="feedback" value="0" id="negative" class="hidden negative-feedback">
                    <label for="negative" class="w-10 text-center negative-feedback-label hover:text-danger-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="font-semibold">No</p>
                    </label>
                </div>

                <x-button type="Submit" class="flex items-center justify-center font-bold rounded-full text-secondary-100 bg-secondary-800 w-28 hover:text-secondary-100 hover:bg-primary-500">
                    Submit
                </x-button>
            </form>

            <div id="flash" class="hidden px-4 py-2 font-bold bg-white border rounded-md shadow-md text-primary-600">
            </div>
        </div>
    </div>
    <!-- End of Feedback Section -->

    <!-- call to action //links to contact page -->
    <div class="flex justify-center px-4 bg-primary-300">
        <div class="flex flex-col items-center justify-between w-11/12 my-12 py-14 md:flex-row">
            <div class="max-w-lg p-8 border-2 border-secondary-800 sm:p-12 md:mb-0">
                <p class="text-2xl font-bold md:text-3xl">Feel free to contact us if this article did not answer all your questions.</p>
            </div>

            <div class="w-1 h-12 border-l-2 border-dashed border-secondary-900 md:border-t-2 md:w-full md:h-1"></div>

            <!-- <button class="rounded-full bg-secondary-800"><a class="inline-block px-6 py-4 text-2xl font-bold text-white" href="{{ route('contact') }}">Get in touch</a></button> -->
            <button class="flex-shrink-0 rounded-full bg-secondary-800 hover:bg-primary-500 hover:shadow-md"><a class="inline-block px-6 py-4 text-2xl font-bold text-white" href="{{ route('contact') }}">Get in touch</a></button>
        </div>
    </div>
    <!-- end of call to action -->
</x-landing-layout>