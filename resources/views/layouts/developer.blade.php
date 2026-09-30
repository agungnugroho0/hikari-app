<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Developer Console' }} | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="icon" href="{{ asset('favicon.ico') }}">
</head>

<body class="min-h-screen bg-neutral-50 font-sans text-neutral-900">
    <div class="min-h-screen bg-[linear-gradient(135deg,rgba(248,250,249,0.96),rgba(241,245,244,0.96))]">
        <header class="border-b border-neutral-200 bg-white">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
                <a href="{{ route('dev.dashboard') }}" class="flex min-w-0 items-center gap-3">
                    <img src="{{ asset('img/logo.png') }}" alt="Hikari Gakkou" class="h-10 w-10 object-contain">
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-semibold text-neutral-900">Hikari Gakkou</span>
                        <span class="block text-xs text-neutral-500">Developer Console</span>
                    </span>
                </a>

                <div class="flex items-center gap-3 sm:gap-5">
                    <nav aria-label="Navigasi developer" class="flex items-center gap-4 text-sm font-medium">
                        <a href="{{ route('dev.dashboard') }}" wire:navigate class="text-neutral-700 hover:text-red-800">Dashboard</a>
                        <a href="{{ route('dev.staff') }}" wire:navigate class="text-neutral-700 hover:text-red-800">Staf</a>
                        <a href="{{ route('dev.announcements') }}" wire:navigate class="text-neutral-700 hover:text-red-800">Buat Notifikasi</a>
                    </nav>
                    <span class="hidden text-sm text-neutral-600 sm:block">{{ Auth::user()->username }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex min-h-10 items-center gap-2 rounded-md border border-neutral-300 px-3 text-sm font-medium text-neutral-700 transition hover:border-red-800 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-800 focus:ring-offset-2">
                            <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 8V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2v-3M10 12h10m0 0-3-3m3 3-3 3" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
            <livewire:announcement-center />
            {{ $slot }}
        </main>

        <footer class="mx-auto max-w-7xl px-4 pb-6 text-xs text-neutral-500 sm:px-6 lg:px-8">
            Hikari Gakkou <span aria-hidden="true">/</span> Developer Console
        </footer>
    </div>

    @livewireScripts
</body>

</html>