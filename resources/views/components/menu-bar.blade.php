<div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
    <a href="{{ route('siswa') }}" wire:navigate
        @class([
            'rounded-2xl border px-4 py-3 text-center text-sm font-semibold shadow-sm transition',
            'border-red-200 bg-red-50 text-red-900' => request()->routeIs('siswa'),
            'border-neutral-200 bg-white text-neutral-700 hover:border-red-200 hover:text-red-900' => !request()->routeIs('siswa'),
        ])>
        Siswa
    </a>
    <a href="{{ route('presensi') }}" wire:navigate
        @class([
            'rounded-2xl border px-4 py-3 text-center text-sm font-semibold shadow-sm transition',
            'border-red-200 bg-red-50 text-red-900' => request()->routeIs('presensi'),
            'border-neutral-200 bg-white text-neutral-700 hover:border-red-200 hover:text-red-900' => !request()->routeIs('presensi'),
        ])>
        Presensi
    </a>
    <a href="{{ route('sensei.laporan') }}" wire:navigate
        @class([
            'rounded-2xl border px-4 py-3 text-center text-sm font-semibold shadow-sm transition',
            'border-red-200 bg-red-50 text-red-900' => request()->routeIs('sensei.laporan'),
            'border-neutral-200 bg-white text-neutral-700 hover:border-red-200 hover:text-red-900' => !request()->routeIs('sensei.laporan'),
        ])>
        Laporan
    </a>
    <a href="{{ route('sensei.profil') }}" wire:navigate
        @class([
            'rounded-2xl border px-4 py-3 text-center text-sm font-semibold shadow-sm transition',
            'border-red-200 bg-red-50 text-red-900' => request()->routeIs('sensei.profil'),
            'border-neutral-200 bg-white text-neutral-700 hover:border-red-200 hover:text-red-900' => !request()->routeIs('sensei.profil'),
        ])>
        Profil
    </a>
</div>
