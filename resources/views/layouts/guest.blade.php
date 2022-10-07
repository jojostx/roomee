<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Roomee's user profile page">
    <meta name="author" content="Roomee.Inc">
    <meta name="keywords" content="Roommate, Housing, University, flatmate, Joint-rental, dorm, off-campus, renting, cohabit, roomies, roomie, finder">

    <title>{{ $title ?? config('app.name', 'Roomee') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @livewireStyles

    <!-- Scripts -->
    <script src="{{ asset('js/tabula_rasa.js') }}" defer></script>
    @livewireScripts
    @stack('scripts')
</head>

<body class="relative font-sans antialiased text-secondary-900">
    @auth
    <header class="relative z-20 flex flex-row items-center justify-center w-full bg-white border-b ">
        @include('sections.navbar-alt')
    </header>
    @endauth

    <main class="min-h-[720px] h-full bg-secondary-100">
        {{ $slot }}
    </main>

    @include('sections.footer-alt')

    @livewire('livewire-ui-modal')
    <script type="application/javascript">
        function setCustomCSSViewportHeightVariable() {
            // First we get the viewport height and we multiple it by 1% to get a value for a vh unit
            let vh = window.innerHeight * 0.01;

            // Then we set the value in the --vh custom property to the root of the document
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }

        setCustomCSSViewportHeightVariable();

        window.addEventListener('resize', () => setCustomCSSViewportHeightVariable());
    </script>

    <x-livewire.includes.user-interaction-menu />
</body>

</html>