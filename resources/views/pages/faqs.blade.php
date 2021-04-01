<x-landing-layout>
    <x-slot name="banner">
        <div class="flex items-center justify-center text-center bg-blue-100 h-60 md:h-80">
        <p class="mt-10 mb-6 text-3xl font-extrabold text-gray-700 md:text-4xl lg:text-5xl">Frequently Asked Questions</p>
        </div>
    </x-slot>

    <div class="absolute top-0 h-screen hero">

    </div>

    <!-- FAQs Accordion -->
    <div class="px-4">
        <div class="flex flex-col w-full py-16 mx-auto md:w-4/5">
            @component('components.faq',
            ['QandA' => [
            "General" => [
            "What is Roomee?" => "Roomee is a roommate search and recommendation service.",
            "Is Roomee a real estate service?" => "Although Roomee provides ease of accesing a roommate, roomee is not a typical real estate service.",
            "Is Roomee a dating platform?" => "No, Roomee does not provide dating and romantic match-making services.",
            "Is Roomee a social network platform?" => "No, Roomee is not a social network platform but roomee offers chat functionalities common to most social network.",
            "Is Roomee owned by any higher educational institution?" => "No, Roomee is an independent and free-to-use e-service.",
            "Is Roomee owned by any higher educational institution?" => "No, Roomee is an independent roommate finder e-service.",
            "Does roomee charge a fee for it's services?" => "No, Roomee is absolutely free to use.",
            "Do I need anything special to use Roomee?" => "No, you can access all of Roomee's services after signing up and completing your profile details.",
            ],
            "Roommate Recommendations" => [
            "How do I find a roommate on Roomee" => "After signing up and updating your profile details correctly,recommended roommates will be displayed to you, you could also search and filter roommates to your choice.",
            "How does roomee recommend roommates to me" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
            "Will I recieve roommate recommendations from the opposite sex?" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
            "Will Roomee recommend roommates from other locations?" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
            "Can I search for a roommate by myself on Roomee?" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
            "How many roommate requests can I send on Roomee?" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
            "How many Roomee users can I add to my favorites lists?" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
            "Can I restrict the number of roommate requests I can recieve?" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
            ],
            "Messaging" => [
            "How do I send a message to a user I want as a roommate?" => "result1",
            "Who can I chat with on Roomee?" => "result1",
            "Are my chats private?" => "result1",
            "Can I chat with a roommate match on other messaging platform?" => "result1",
            "What information can i share with others in a chat?" => "result1",
            "Can I delete my chat history with a user?" => "result1",
            ],
            "Roomee's policies and reporting" => [
            "Can I block a user from sending me roommate request?" => "result",
            "How do I remove my account from the search results?" => "result",
            "What issues can I report a user for?" => "result",
            "What can I do if my account is blocked?" => "result",
            "Why is my account blocked?" => "result",
            ],
            "Profile and Account" => [
            "How do I verify my email address?" => "result",
            "How can I change my password?" => "result",
            "How do I reset my password if I've forgotten it?" => "result",
            "Why can't I update all my profile information?" => "result",
            "How do I deactivate my account?" => "result",
            "How do I reactivate my account?" => "result",
            ],
            ]
            ])
            @endcomponent
        </div>
    </div>
    <!-- End Of Accordion -->

    <!-- Feedback Section -->
    <div class="py-16 bg-gray-100">
        <div class="flex flex-col items-center w-10/12 mx-auto">
            <p class="mb-4 text-xl font-semibold">Was this article helpful?</p>
            <form x-on:submit.prevent="submitForm('http://127.0.0.1:8000/faqs')"
                  x-data="form('feedback_Form')"
                  method="POST"
                  id="feedback_Form"
                  class="flex flex-col items-center justify-center mb-4">
                @csrf

                <div class="flex mb-4">
                    <input type="radio" x-on:change=" appear = false " name="feedback" value="1" id="positive" class="hidden positive-feedback">
                    <label for="positive" class="w-10 mr-4 text-center positive-feedback-label hover:text-yellow-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="font-semibold">Yes</p>
                    </label>
                    <input type="radio" x-on:change=" appear = true " name="feedback" value="0" id="negative" class="hidden negative-feedback">
                    <label for="negative" class="w-10 text-center negative-feedback-label hover:text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="font-semibold">No</p>
                    </label>
                </div>

                <x-button type="Submit" class="flex items-center justify-center font-bold text-gray-100 bg-gray-800 rounded-full w-28 hover:text-gray-100 hover:bg-blue-500">
                    Submit
                </x-button>
            </form>

            <div id="flash" class="hidden px-4 py-2 font-bold text-blue-600 bg-white border rounded-md shadow-md">
            </div>

        </div>
    </div>
    <!-- End of Feedback Section -->

    <!-- call to action //links to contact page -->
    <div class="flex justify-center bg-blue-300">
        <div class="flex flex-col items-center justify-between w-11/12 my-12 py-14 md:flex-row">
            <div class="max-w-lg p-8 border-2 border-gray-800 sm:p-12 md:mb-0">
                <p class="text-2xl font-bold md:text-3xl">Feel free to contact us if this article did not answer all your questions.</p>
            </div>

            <div class="w-1 h-12 border-l-2 border-gray-900 border-dashed md:border-t-2 md:w-full md:h-1"></div>

            <!-- <button class="bg-gray-800 rounded-full"><a class="inline-block px-6 py-4 text-2xl font-bold text-white" href="{{ route('contact') }}">Get in touch</a></button> -->
            <button class="flex-shrink-0 bg-gray-800 rounded-full hover:bg-blue-500 hover:shadow-md"><a class="inline-block px-6 py-4 text-2xl font-bold text-white" href="{{ route('contact') }}">Get in touch</a></button>
        </div>
    </div>
    <!-- end of call to action -->

</x-landing-layout>
