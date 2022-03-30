<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Roomee's user profile page">
    <meta name="author" content="Roomee.Inc">
    <meta name="keywords" content="Roommate, Housing, University, flatmate, Joint-rental, dorm, off-campus, renting, cohabit, roomies, roomie, finder">

    <title>{{ config('app.name', 'Roomee') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <script src="{{ asset('js/tabula_rasa.js') }}" defer></script>
    @livewireStyles
</head>

<body class="relative min-h-screen font-sans antialiased text-gray-900">
    @auth
    <header class="relative z-40 flex flex-row items-center justify-center w-full bg-white border-b ">
        @include('sections.navbar-alt')
    </header>
    @endauth

    <main>
        {{ $slot }}
    </main>

    @include('sections.footer-alt')
    @livewireScripts
    @stack('scripts')
</body>

</html>