<div class="space-y-4 sm:space-y-5"
    x-on:kirim.window="
        if (window.showToast) {
            window.showToast($event.detail.message, $event.detail.type ?? 'success');
        }
    ">
    <x-dashboard-header
        :foto="$foto"
        :nama="$nama"
        :kelas="$sensei?->kelas?->nama_kelas"
    />

    <x-menu-bar />

    <section class="grid gap-3 sm:grid-cols-3">
        <x-stat-card
            title="Kelas"
            :value="$sensei?->kelas?->nama_kelas ?? '-'"
            description="Kelas aktif yang sedang Anda ampu"
        />

        <x-stat-card
            title="Total Siswa"
            :value="$siswa->kelas->core->count()"
            description="Daftar siswa aktif di kelas ini"
        />

    </section>

    <section class="rounded-[28px] border border-neutral-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-900/70">Daftar Siswa</p>
                <h2 class="mt-1 text-lg font-semibold text-neutral-900">Siswa di kelas {{ $sensei?->kelas?->nama_kelas ?? '-' }}</h2>
                <p class="mt-1 text-sm text-neutral-500">Tampilan daftar siswa dibuat lebih ringkas agar konsisten dengan dashboard guru.</p>
            </div>

            <a href="{{ route('home') }}" wire:navigate
                class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-neutral-200 bg-neutral-50 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100">
                Kembali ke Dashboard
            </a>
        </div>

        <div class="mt-5 grid gap-3">
            @forelse ($siswa->kelas->core as $student)
                <article class="flex flex-col gap-3 rounded-3xl border border-neutral-200 bg-neutral-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <p class="text-base font-semibold text-neutral-900">
                            {{ $student->detail->nama_lengkap ?? $student->nis }}
                        </p>
                        <div class="mt-1 flex flex-wrap gap-x-3 gap-y-1 text-sm text-neutral-500">
                            <span>NIS {{ $student->nis }}</span>
                            <span>{{ $student->detail->panggilan ?? 'Tanpa panggilan' }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if ($canPromoteStudents)
                            <button type="button"
                                wire:click="naikKelas('{{ $student->nis }}')"
                                wire:loading.attr="disabled"
                                wire:target="naikKelas('{{ $student->nis }}')"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-700 text-lg font-bold text-white transition hover:bg-blue-600 disabled:cursor-not-allowed disabled:bg-blue-300"
                                title="Naik kelas"
                                aria-label="Naik kelas {{ $student->detail->nama_lengkap ?? $student->nis }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 19V5M12 5l-5 5M12 5l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        @endif

                        @if (optional($student->detail)->wa)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', optional($student->detail)->wa) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex min-h-11 items-center justify-center rounded-2xl bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-600">
                                Hubungi Siswa
                            </a>
                        @else
                            <span class="inline-flex min-h-11 items-center justify-center rounded-2xl bg-neutral-200 px-4 py-2 text-sm font-semibold text-neutral-500">
                                Nomor belum tersedia
                            </span>
                        @endif
                    </div>
                </article>
            @empty
                <div class="flex min-h-48 items-center justify-center rounded-3xl bg-neutral-50 p-6 text-center text-sm text-neutral-500">
                    Belum ada siswa aktif yang terdaftar di kelas ini.
                </div>
            @endforelse
        </div>
    </section>
</div>
