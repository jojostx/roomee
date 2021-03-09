<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Roomee') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/welcome.js') }}" defer></script>
</head>

<body class="relative min-h-screen overflow-x-hidden font-sans antialiased bg-gray-100 vh">

    <!-- Navigation Bar -->
    <div class="z-40 flex flex-row items-center justify-center w-full h-16 bg-gray-900 border-b border-gray-800">
        @include('sections.navbar')
    </div>
    <!-- End of Navigation Bar -->



    <!-- Page Content -->
    <main class="relative">

        {{ $banner }}

        {{ $slot }}
    </main>
    <!-- End of page content -->

    @include('sections.footer')



    <button onclick="backToTop()" class="fixed z-40 hidden px-4 py-2 text-sm leading-none bg-gray-700 rounded-full shadow-lg topButton focus:outline-black focus:bg-gray-600 hover:bg-blue-600 text-gray-50 bottom-8 right-8">
        <p class="mr-2 font-semibold">Back to top</p>
        <i class="block w-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
        </i>
    </button>

    <script>
        const backToTopButton = document.querySelector('.topButton');

        function inViewport(elem, callback, options = {}) {
            return new IntersectionObserver(entries => {
                entries.forEach(entry =>
                    callback(entry)
                )
            }, options).observe(document.querySelector(elem));
        };

        inViewport('.hero', entry => {
            if (!entry.isIntersecting) {
                backToTopButton.classList.add('flex');
                backToTopButton.classList.remove('hidden');
            } else {
                backToTopButton.classList.add('hidden');
                backToTopButton.classList.remove('flex');
            }
        });

        function backToTop() {
            document.documentElement.scrollTo({
                top: 0,
                behavior: "smooth"
            })
        };



        function form(formElem) {

            return {
                submitForm: () => {
                    const formElement = document.getElementById(formElem);
                    const formDataElem = new FormData(formElement);

                    // for (const key of formDataElem.keys()) {
                    //     console.log(`${key} : ${formDataElem.get(key).length}`);
                    // }

                    const url = 'http://127.0.0.1:8000/faqs';
                    const req = new Request(
                        url, {
                            body: formDataElem,
                            method: 'POST',
                        }
                    );

                    fetch(req)
                        .then(res => res.json()).then(
                            (data) => {
                                    
                                let responseText;
                                if (data.success) {
                                    responseText = data.success;
                                    const successDiv = document.querySelector('#success');
                                    successDiv.innerHTML = `<p>${responseText}</p>`;
                                    successDiv.classList.remove('hidden')

                                    setTimeout(() => {
                                        successDiv.classList.add('hidden')
                                    }, 2000)
                                } 
                            }
                        )
                        .catch(err => {
                            if (err) {
                                    let responseText = 'Unable to submit feedback';
                                    const successDiv = document.querySelector('#success');
                                    successDiv.innerHTML = `<p>${responseText}</p>`;
                                    successDiv.classList.remove('hidden')

                                    setTimeout(() => {
                                        successDiv.classList.add('hidden')
                                    }, 2000) 
                                }
                        })
                },
            }
        };
    </script>
</body>

</html>