@props([
    'foto' => null,
    'nama' => '-',
    'kelas' => '-',
])

<section class="rounded-[28px] border border-neutral-200 bg-white p-4 shadow-sm sm:p-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <img
                src="{{ $foto ? asset('storage/' . $foto) : asset('img/logo.jpg') }}"
                alt="{{ $nama }}"
                class="h-12 w-12 rounded-2xl object-cover shadow-sm"
            >

            <div class="min-w-0">
                <p class="text-xs uppercase tracking-[0.2em] text-red-900/70">Dashboard Guru</p>
                <h1 class="truncate text-xl font-bold text-neutral-900 sm:text-2xl">{{ $nama }}</h1>
                <p class="text-sm text-neutral-500">Kelas {{ $kelas ?: '-' }}</p>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="w-full sm:w-auto">
            @csrf
            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-2xl border border-neutral-200 bg-neutral-50 px-4 py-3 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 sm:w-auto"
            >
                Logout
            </button>
        </form>
    </div>
</section>
