<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @stack('styles')
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    {{-- <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}?v=2"> --}}

</head>

<body>
    <div class="p-4 sm:mx-24 sm:flex">
        <x-nav-bar class=""></x-nav-bar>
        <div class="p-4 border-r-2 border-default w-full">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>
