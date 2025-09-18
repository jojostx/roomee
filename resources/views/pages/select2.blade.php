@php
  $options = App\Models\Hobby::all(['name', 'id'])->toArray();
  $selectedOptions = auth()->user()->hobbies()->pluck('hobbies.id')->toArray();
@endphp

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
    [x-cloak] {
      display: none !important;
    }
  </style>

  <!-- Scripts -->
  <script src="{{ asset('js/welcome.js') }}" defer></script>
</head>

<body class="relative min-h-screen overflow-x-hidden font-sans antialiased vh">

  <!-- Page Content -->
  <main class="relative">
    <div class="flex items-center justify-center min-h-screen">
      <div class="max-w-md">
        <x-livewire.multi-select :name="'hobbies'" :options="$options" :selectedOptions="$selectedOptions" :label="'hobbies'" :isRequired="true" />
      </div>
    </div>
  </main>

</body>

@stack('scripts')

</html>