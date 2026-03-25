<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @stack('styles')
</head>

<body>
    @php
        // $nama = Auth::user()->nama_s;
        // $foto = Auth::user()->foto_s;
    @endphp
        <div class="p-4 border-default sm:mx-90">
            {{-- {{ $nama }} --}}
            {{ $slot }}
        </div>
    {{-- </div> --}}

    @livewireScripts
    @stack('scripts')
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>


</body>

</html>
