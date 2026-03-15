@php
    $chatAutoOpen    = auth()->check() && request()->routeIs('chat.*');
    $chatInitialRoom = null;

    if (auth()->check() && request()->routeIs('chat.room')) {
        $chatRouteRoom = request()->route('chatRoom');

        if ($chatRouteRoom instanceof \App\Models\ChatRoom) {
            $authId = auth()->id();

            if ((int) $chatRouteRoom->user_a_id === $authId || (int) $chatRouteRoom->user_b_id === $authId) {
                $chatInitialRoom = $chatRouteRoom->id;
            }
        }
    }
@endphp
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
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @filamentStyles
    <link rel="stylesheet" href="{{ asset('css/filament/filament/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/tabula_rasa.js'])

    <!-- Scripts -->
    @filamentScripts
    @stack('scripts')
</head>

<body
    class="relative font-sans antialiased text-secondary-900"
    data-chat-auto-open="{{ $chatAutoOpen ? 'true' : 'false' }}"
>
    @auth
    <header class="relative z-20 flex flex-row items-center justify-center w-full bg-white border-b ">
        @include('sections.navbar-alt')
    </header>
    @endauth

    <main class="min-h-full bg-secondary-50">
        {{ $slot }}
    </main>

    @include('sections.footer-alt')

    @auth
    {{-- ================================================================ --}}
    {{-- Chat drawer overlay — lazy-loaded on first open                  --}}
    {{-- ================================================================ --}}
    <div
        x-show="$store.chat.open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-white"
        style="display: none;"
    >
        <livewire:components.chat.chat-drawer :initial-room-id="$chatInitialRoom" wire:lazy />
    </div>
    @endauth

    @livewire('wire-elements-modal')
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
