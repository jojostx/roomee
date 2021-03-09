<x-landing-layout>
    <!-- hero section -->
    <x-slot name="banner">
        <div class="flex flex-row justify-center bg-blue-100 hero">
            <div class="relative flex flex-col items-center justify-between w-11/12 py-10 mb-20 md:mt-20 md:flex-row md:h-80">
                <div class="mb-16 text-center md:mb-0 md:mr-16 sm:text-left">
                    <p class="mt-10 mb-6 text-3xl font-extrabold text-gray-700 md:text-4xl lg:text-5xl">Who are we?</p>
                    <p class="max-w-md px-8 sm:px-0"><span class="font-bold">Roomee</span> is a roommate finder platform that provides a simple yet effective way for you to find the best roommate based on your preferences. Founded in 2020, by <span class="font-bold">Ikuru John</span>, we are based in Abuja, Nigeria.</p>
                </div>
                <div class="relative w-full max-w-xs badge">
                    <img src="{{ asset('images/badge.png') }}" alt="badge of excellence" class="relative z-20">
                </div>
            </div>
        </div>
    </x-slot>
    <!-- end of hero section -->

    <!-- company goals and motivation section -->
    <div class="flex flex-row justify-center bg-white md:pt-12">
        <div class="flex flex-col items-center w-11/12 my-20">
            <div class="flex flex-col items-center justify-between w-full my-2 md:mt-12 md:flex-row">
                <div class="max-w-md p-8 border-2 border-gray-800 sm:p-8 md:mb-0">
                    <h2 class="mb-4 text-xl font-bold">OUR MOTIVATION</h2>
                    <p class="font-normal">The insatiable urge to be of help to people and the drive to create solutions to common problems as a way to build a better world for the next generation.</p>
                </div>

                <div class="w-1 h-12 border-l-2 border-gray-900 border-dashed md:border-t-2 md:w-full md:h-1"></div>

                <!-- <button class="bg-gray-800 rounded-full"><a class="inline-block px-6 py-4 text-2xl font-bold text-white" href="{{ route('contact') }}">Get in touch</a></button> -->
                <div class="flex-shrink-0 w-full md:max-w-md"><img src="{{ asset('images/goal.jpg') }}" alt="our motivation"></div>
                <!-- <button class="flex-shrink-0 bg-gray-800 rounded-full hover:bg-blue-500 hover:shadow-md"><a class="inline-block px-6 py-4 text-2xl font-bold text-white" href="{{ route('contact') }}">Get in touch</a></button> -->
            </div>
            <div class="flex flex-col-reverse items-center w-full mt-12 md:items-center md:flex-row md:justify-between">
                <div class="flex-shrink-0 w-full md:max-w-md"><img src="{{ asset('images/motivation.jpg') }}" alt="our motivation"></div>

                <div class="w-1 h-12 border-l-2 border-gray-900 border-dashed md:border-t-2 md:w-full md:h-1"></div>


                <div class="max-w-md p-8 border-2 border-gray-800 sm:p-8 md:mb-0">
                    <h2 class="mb-4 text-xl font-bold">OUR MISSION</h2>
                    <p class="font-normal">The insatiable urge to be of help to people and the drive to create solutions to common problems as a way to build a better world for the next generation. Making the lives of Nigerians easier by providing solutions to commons problems through the use of technology. We are focused on creating an encouraging environment for young innovative minds.</p>
                </div>
            </div>
            <!-- <div class="flex flex-col items-start w-full md:items-center md:flex-row md:justify-between">
                <div class="flex-shrink-0 max-w-md mb-8 mr-8 w-96 md:mb-0">
                    <h2 class="mb-4 text-xl font-bold">OUR MOTIVATION</h2>
                    <p class="font-normal">The insatiable urge to be of help to people and the drive to create solutions to common problems as a way to build a better world for the next generation.</p>
                </div>
                <div class="w-full md:max-w-md "><img src="{{ asset('images/goal.jpg') }}" alt="our motivation"></div>
            </div> -->
            <!-- <div class="flex flex-col-reverse items-start w-full md:items-center md:flex-row md:justify-between mt-28">
                <div class="w-full mr-8 md:max-w-md "><img src="{{ asset('images/motivation.jpg') }}" alt="our motivation"></div>

                <div class="flex-shrink-0 max-w-md mb-8 w-96 md:mb-0">
                    <h2 class="mb-4 text-xl font-bold">OUR MISSION</h2>
                    <p class="font-normal">The insatiable urge to be of help to people and the drive to create solutions to common problems as a way to build a better world for the next generation.Making the lives of Nigerians easier by providing solutions to commons problems through the use of technology. We are focused on creating an encouraging environment for young innovative minds.</p>
                </div>

            </div> -->
        </div>
    </div>
    <!-- end of company goals and motivation section -->

    <!-- our team  section-->
    <div class="flex flex-col items-center md:justify-center md:flex-row">
        <div class="flex flex-col items-center w-11/12 py-10 my-12">
            <div>
                <p class="text-3xl font-bold text-center">Meet The Team</p>
            </div>
            <div class="grid max-w-4xl grid-flow-row grid-cols-2 gap-6 mx-auto mt-8 mb-6 sm:grid-cols-4 gap-y-12">
                <div>
                    <img src="{{ asset('images/founder.jpg') }}" alt="roomee's founder" class="mb-4">
                    <!-- <div>
                        <h2 class="text-lg font-bold">Ikuru John</h2>
                        <p class="text-sm">CEO & Founder</p>
                    </div> -->
                    <div class="items-center justify-between lg:flex">
                        <div class="mb-2">
                            <h2 class="text-lg font-bold">Ikuru John</h2>
                            <p class="text-sm">CEO & Founder</p>
                        </div>
                        <x-social-link class="text-gray-800 cursor-pointer hover:text-blue-500">
                            <path fill="currentColor" fill-rule="evenodd" stroke="none" d="M10 0C4.48565 0 0 4.48565 0 10C0 15.5143 4.48565 20 10 20C15.5143 20 20 15.5143 20 10C20 4.48565 15.5143 0 10 0L10 0ZM14.8865 8.05383C14.8908 8.15644 14.8934 8.26078 14.8934 8.36557C14.8934 11.5473 12.4704 15.2173 8.04037 15.2173C6.68037 15.2173 5.41385 14.8186 4.34863 14.1351C4.53689 14.1577 4.72776 14.1686 4.92298 14.1686C6.05168 14.1686 7.09037 13.7843 7.91385 13.1382C6.85994 13.119 5.97124 12.4212 5.66385 11.4656C5.81124 11.4938 5.96211 11.509 6.11776 11.509C6.33733 11.509 6.55037 11.4799 6.75168 11.4243C5.65081 11.2021 4.82081 10.2299 4.82081 9.06252C4.82081 9.05209 4.82081 9.04209 4.82081 9.03209C5.14559 9.21252 5.51646 9.32122 5.91168 9.33383C5.26472 8.90122 4.84037 8.1647 4.84037 7.32905C4.84037 6.88774 4.95863 6.47426 5.16602 6.11774C6.35428 7.5747 8.12907 8.53339 10.1304 8.63426C10.0891 8.45774 10.0673 8.27426 10.0673 8.08557C10.0673 6.75557 11.1456 5.67731 12.476 5.67731C13.1686 5.67731 13.7943 5.96948 14.2334 6.43774C14.7817 6.32992 15.2978 6.12948 15.763 5.85383C15.5834 6.41557 15.2012 6.88774 14.7034 7.186C15.1912 7.12687 15.6547 6.99818 16.0873 6.80644C15.7647 7.28948 15.3565 7.71426 14.8865 8.05383L14.8865 8.05383Z" />
                        </x-social-link>
                    </div>
                </div>
                <div>
                    <img src="{{ asset('images/co-founder.jpg') }}" alt="roomee's founder" class="mb-4">
                    <!-- <div>
                        <h2 class="text-lg font-bold">Grace Ubamara</h2>
                        <p class="text-sm">Co-Founder</p>
                    </div> -->
                    <div class="items-center justify-between lg:flex">
                        <div class="mb-2">
                            <h2 class="text-lg font-bold">Grace Ubamara</h2>
                            <p class="text-sm">Co-Founder</p>
                        </div>
                        <x-social-link class="text-gray-800 cursor-pointer hover:text-blue-500">
                            <path fill="currentColor" fill-rule="evenodd" stroke="none" d="M10 0C4.4775 0 0 4.50057 0 10.0515C0 15.0907 3.69333 19.252 8.505 19.9791L8.505 12.7152L6.03083 12.7152L6.03083 10.0733L8.505 10.0733L8.505 8.31512C8.505 5.40436 9.91583 4.12698 12.3225 4.12698C13.475 4.12698 14.085 4.21326 14.3733 4.25179L14.3733 6.55778L12.7317 6.55778C11.71 6.55778 11.3533 7.53194 11.3533 8.62923L11.3533 10.0733L14.3475 10.0733L13.9417 12.7152L11.3533 12.7152L11.3533 20C16.2342 19.3349 20 15.1401 20 10.0515C20 4.50057 15.5225 0 10 0L10 0Z" />
                        </x-social-link>
                    </div>
                </div>
                <div>
                    <img src="{{ asset('images/fullstack.jpg') }}" alt="roomee's founder" class="mb-4">
                    <div class="items-center justify-between lg:flex">
                        <div class="mb-2">
                            <h2 class="text-lg font-bold">Phillip Colt</h2>
                            <p class="text-sm">Full-Stack Developer</p>
                        </div>
                        <x-social-link class="text-gray-800 cursor-pointer hover:text-blue-500">
                            <path fill="currentColor" fill-rule="evenodd" stroke="none" d="M10 0C4.48565 0 0 4.48565 0 10C0 15.5143 4.48565 20 10 20C15.5143 20 20 15.5143 20 10C20 4.48565 15.5143 0 10 0L10 0ZM14.8865 8.05383C14.8908 8.15644 14.8934 8.26078 14.8934 8.36557C14.8934 11.5473 12.4704 15.2173 8.04037 15.2173C6.68037 15.2173 5.41385 14.8186 4.34863 14.1351C4.53689 14.1577 4.72776 14.1686 4.92298 14.1686C6.05168 14.1686 7.09037 13.7843 7.91385 13.1382C6.85994 13.119 5.97124 12.4212 5.66385 11.4656C5.81124 11.4938 5.96211 11.509 6.11776 11.509C6.33733 11.509 6.55037 11.4799 6.75168 11.4243C5.65081 11.2021 4.82081 10.2299 4.82081 9.06252C4.82081 9.05209 4.82081 9.04209 4.82081 9.03209C5.14559 9.21252 5.51646 9.32122 5.91168 9.33383C5.26472 8.90122 4.84037 8.1647 4.84037 7.32905C4.84037 6.88774 4.95863 6.47426 5.16602 6.11774C6.35428 7.5747 8.12907 8.53339 10.1304 8.63426C10.0891 8.45774 10.0673 8.27426 10.0673 8.08557C10.0673 6.75557 11.1456 5.67731 12.476 5.67731C13.1686 5.67731 13.7943 5.96948 14.2334 6.43774C14.7817 6.32992 15.2978 6.12948 15.763 5.85383C15.5834 6.41557 15.2012 6.88774 14.7034 7.186C15.1912 7.12687 15.6547 6.99818 16.0873 6.80644C15.7647 7.28948 15.3565 7.71426 14.8865 8.05383L14.8865 8.05383Z" />
                        </x-social-link>
                    </div>
                </div>
                <div>
                    <img src="{{ asset('images/jonas.jpg') }}" alt="roomee's founder" class="mb-4">
                    <!-- <div>
                        <h2 class="text-lg font-bold">Cole Obaro</h2>
                        <p class="text-sm">UI & UX Designer</p>
                    </div> -->
                    <div class="items-center justify-between lg:flex">
                        <div class="mb-2">
                            <h2 class="text-lg font-bold">Cole Obaro</h2>
                            <p class="text-sm">UI & UX Designer</p>
                        </div>
                        <x-social-link class="text-gray-800 cursor-pointer hover:text-blue-500">
                            <path fill="currentColor" fill-rule="evenodd" stroke="none" d="M15.125 10.9997C14.424 10.9997 13.738 11.0612 13.0685 11.1737C13.9855 13.5376 14.6805 16.0096 15.132 18.566C17.5154 17.1325 19.2579 14.7421 19.8099 11.9231C18.3639 11.3297 16.7824 10.9997 15.125 10.9997L15.125 10.9997ZM11.0113 10.1223C10.7438 9.51531 10.4598 8.91682 10.1618 8.32683C8.08233 9.08532 5.83888 9.49981 3.49993 9.49981C2.31895 9.49981 1.16448 9.38831 0.040999 9.18631C0.0194996 9.45531 0 9.7253 0 9.9998C0 12.7022 1.08198 15.1537 2.83044 16.9551C4.5284 13.7162 7.46584 11.2253 11.0113 10.1223L11.0113 10.1223ZM11.5527 7.75237C11.8822 8.40986 12.1972 9.07585 12.4907 9.75383C13.3441 9.58884 14.2241 9.49984 15.1251 9.49984C16.8331 9.49984 18.4685 9.81183 19.981 10.3788C19.9855 10.2518 20 10.1278 20 9.99983C20 7.50988 19.079 5.23443 17.5681 3.48246C15.8621 5.26193 13.8241 6.7179 11.5527 7.75237L11.5527 7.75237ZM3.99246 17.9745C5.66743 19.2395 7.74389 19.9994 9.99984 19.9994C11.3203 19.9994 12.5793 19.736 13.7343 19.269C13.2918 16.58 12.5653 13.9871 11.5913 11.5166C8.25037 12.5136 5.49693 14.8856 3.99246 17.9745L3.99246 17.9745ZM16.5067 2.42245C14.7557 0.916981 12.4843 0 9.99933 0C8.90886 0 7.86138 0.181496 6.8779 0.505489C8.37287 2.34895 9.70884 4.32591 10.8593 6.41987C12.9948 5.45889 14.9092 4.09441 16.5067 2.42245L16.5067 2.42245ZM0.275514 7.70487C1.32249 7.89537 2.39847 7.99986 3.49945 7.99986C5.5859 7.99986 7.58886 7.63887 9.45382 6.98339C8.29585 4.89993 6.95337 2.93297 5.4409 1.11051C2.87396 2.43198 0.954999 4.82893 0.275514 7.70487L0.275514 7.70487Z" />
                        </x-social-link>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- end of our team section -->



    <!-- call to action //links to contact page -->
    <div class="flex justify-center bg-blue-300">
        <div class="flex flex-col items-center justify-between w-11/12 my-12 py-14 md:flex-row">
            <div class="max-w-lg p-8 border-2 border-gray-800 sm:p-10 md:mb-0">
                <p class="text-2xl font-bold md:text-3xl">We are always happy to hear from you. Reach out to us and we will make sure to assist you.</p>
            </div>

            <div class="w-1 h-12 border-l-2 border-gray-900 border-dashed md:border-t-2 md:w-full md:h-1"></div>

            <!-- <button class="bg-gray-800 rounded-full"><a class="inline-block px-6 py-4 text-2xl font-bold text-white" href="{{ route('contact') }}">Get in touch</a></button> -->
            <button class="flex-shrink-0 bg-gray-800 rounded-full hover:bg-blue-500 hover:shadow-md"><a class="inline-block px-6 py-4 text-2xl font-bold text-white" href="{{ route('contact') }}">Get in touch</a></button>
        </div>
    </div>
    <!-- end of call to action -->

</x-landing-layout>