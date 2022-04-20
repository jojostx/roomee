<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Roomee') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Scripts -->
    <script src="{{ asset('js/welcome.js') }}" defer></script>
</head>

<body class="relative min-h-screen overflow-x-hidden font-sans antialiased bg-gray-100 vh">

    <!-- Navigation Bar -->
    <header class="z-40 flex flex-row items-center justify-center w-full h-16 bg-gray-900 border-b border-gray-800">
        @include('sections.navbar')
    </header>
    <!-- End of Navigation Bar -->



    <!-- Page Content -->
    <main class="relative">

        {{ $banner }}

        {{ $slot }}
    </main>
    <!-- End of page content -->

    @include('sections.footer')

    <button onclick="backToTop()" id="topButton" class="fixed z-40 hidden px-4 py-2 text-sm leading-none bg-gray-700 rounded-full shadow-lg focus:outline-black focus:bg-gray-600 hover:bg-blue-600 text-gray-50 bottom-8 right-8">
        <p class="mr-2 font-semibold">Back to top</p>
        <i class="block w-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
        </i>
    </button>

    <script>
        function backToTop() {
            document.documentElement.scrollTo({
                top: 0,
                behavior: "smooth"
            })
        };

        function flashElement(node, flashMessage) {
            const flashNode = document.querySelector(node);
            flashNode.innerHTML = `<p>${flashMessage}</p>`;
            flashNode.classList.remove('hidden')

            setTimeout(() => {
                flashNode.classList.add('hidden')
            }, 2000)
        }
        function form(formElem) {

            return {
                submitForm(form_url) {
                    const formElement = document.getElementById(formElem);
                    const formDataElem = new FormData(formElement);

                    const url = form_url;
                    const req = new Request(
                        url, {
                            body: formDataElem,
                            method: 'POST',
                        }
                    );

                    fetch(req)
                        .then(res => res.json()).then(
                            (data) => {
                                (data.success) ?
                                flashElement('#flash', data.success): (data.error) ? flashElement('#flash', 'Please choose a reation'):console.log('there was an error');
                            }
                        )
                        .catch(err => {
                            let responseText = 'Submission unsuccessful';
                            flashElement('#flash', responseText);
                        })
                },

            }
        };

        const backToTopButton = document.querySelector('#topButton');
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
    </script>
</body>

@stack('scripts')
</html>
