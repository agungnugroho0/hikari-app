<div class="space-y-4 sm:space-y-5">
    <x-dashboard-header
        :foto="$foto"
        :nama="$nama"
        :kelas="$sensei?->kelas?->nama_kelas"
    />

    <x-menu-bar />

    <section class="grid gap-4 xl:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)]">
        <article class="rounded-[28px] border border-neutral-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex flex-col gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-900/70">Presensi Harian</p>
                    <h2 class="mt-1 text-lg font-semibold text-neutral-900">Scan kehadiran siswa</h2>
                    <p class="mt-1 text-sm text-neutral-500">Mulai kamera untuk scan QR, lalu sistem akan mencatat hadir secara otomatis.</p>
                </div>

                <div class="rounded-3xl bg-neutral-50 p-4">
                    <livewire:scan-absen />
                </div>
            </div>
        </article>

        <article class="rounded-[28px] border border-neutral-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-900/70">Kontrol Manual</p>
                    <h2 class="mt-1 text-lg font-semibold text-neutral-900">Daftar siswa belum absen</h2>
                    <p class="mt-1 text-sm text-neutral-500">Gunakan tombol cepat untuk menandai hadir, mensetsu, izin, atau alfa.</p>
                </div>
                <a href="{{ route('home') }}" wire:navigate
                    class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-neutral-200 bg-neutral-50 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100">
                    Kembali
                </a>
            </div>

            <div class="mt-5 rounded-3xl bg-neutral-50 p-3 sm:p-4">
                <livewire:daftar-hadir />
            </div>
        </article>
    </section>
</div>
