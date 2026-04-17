<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @stack('styles')
    <link rel="icon" href="{{ asset('favicon.ico') }}">
{{-- <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}?v=2"> --}}
</head>

<body>
    <main class="min-h-screen bg-neutral-100">
        <div class="mx-auto w-full max-w-6xl px-3 py-4 sm:px-4 sm:py-6 lg:px-6">
            {{ $slot }}
        </div>
    </main>
    @livewireScripts
    @stack('scripts')
    {{-- <script src="../path/to/flowbite/dist/flowbite.min.js"></script> --}}

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</body>
</html>
