<div>
    <div wire:loading wire:target="loadSiswa, buattx, buattagihan, nafuda"
        class="relative mx-auto flex h-full w-full flex-col items-center justify-center gap-2 bg-neutral-primary/95 z-10">

        <div role="status">
            <svg class="h-8 w-8 animate-spin fill-red-900 text-neutral-tertiary" viewBox="0 0 100 101">
                <path fill="currentColor"
                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908Z" />
                <path fill="currentFill"
                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539..." />
            </svg>
        </div>
    </div>

    <div class="h-full">

        @if ($isEditing)
            <livewire:editsiswa :siswa="$siswa" :key="$siswa->nis" />
        @elseif ($tx && $siswa)
            <livewire:form-transaksi :siswa="$siswa" :key="'tx-'.$siswa->nis" />
        @elseif ($tagihan && $siswa)
            <livewire:form-tagihan :siswa="$siswa" :key="'tagihan-'.$siswa->nis" />
        @elseif ($siswa)

            <div class="rounded-2xl bg-gradient-to-r from-amber-50 via-white to-neutral-50 px-4 py-4">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ $siswa->foto ? Storage::url($siswa->foto) : asset('img/logo.png') }}"
                            class="h-14 w-14 rounded-xl object-cover ring-1 ring-neutral-200">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-neutral-400">Profil siswa</p>
                            <h2 class="mt-1 text-lg font-semibold text-neutral-900">{{ optional($siswa->detail)->nama_lengkap }}</h2>
                            <div class="mt-1 flex flex-wrap items-center gap-2 text-sm text-neutral-500">
                                <p>{{ $siswa->nis }} | Kelas {{ optional($siswa->kelas)->nama_kelas ?? '-' }}</p>
                                @if ($siswa->status === 'lolos')
                                    <button
                                        x-on:click.stop="if (confirm('Peringatan: aksi UNFIT akan membatalkan kelulusan siswa, menghapus data kelulusan, dan menghapus tagihan serta transaksi yang terkait SO. Lanjutkan?')) { $wire.unfit() }"
                                        class="rounded bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 transition hover:bg-red-200">
                                        UNFIT
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if ($siswa->foto)
                            <a href="{{ Storage::url($siswa->foto) }}"
                                download="{{ optional($siswa->detail)->nama_lengkap }}"
                                class="rounded-xl bg-white px-3 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50">
                                Download Foto
                            </a>
                        @endif

                        <div wire:click="nafuda"
                            class="cursor-pointer rounded-xl bg-neutral-900 px-3 py-2 text-sm font-medium text-white transition hover:bg-neutral-800">
                            Buat Nafuda
                        </div>
                        <button wire:click.prevent="buattx"
                            class="cursor-pointer rounded-xl bg-amber-900 px-3 py-2 text-sm font-medium text-white transition hover:bg-amber-800">
                            Buat Transaksi
                        </button>
                        <button wire:click.prevent="buattagihan"
                            class="cursor-pointer rounded-xl bg-emerald-700 px-3 py-2 text-sm font-medium text-white transition hover:bg-emerald-600">
                            Buat Tagihan
                        </button>

                        <a href="https://wa.me/{{ optional($siswa->detail)->wa }}"
                            class="rounded-xl bg-green-700 px-3 py-2 text-sm font-medium text-white transition hover:bg-green-600">
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-4 grid gap-4 xl:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]">
                <section class="rounded-2xl bg-white p-4">
                    <div class="mb-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-neutral-400">Informasi dasar</p>
                        <h3 class="mt-1 text-base font-semibold text-neutral-900">Detail pribadi</h3>
                    </div>

                    <dl class="grid gap-3 sm:grid-cols-2">
                        <div class="rounded-xl bg-neutral-50 p-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-neutral-500">Nama lengkap</dt>
                            <dd class="mt-2 text-sm font-medium text-neutral-900">{{ optional($siswa->detail)->nama_lengkap ?: '-' }}</dd>
                        </div>
                        <div class="rounded-xl bg-neutral-50 p-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-neutral-500">Panggilan</dt>
                            <dd class="mt-2 text-sm font-medium text-neutral-900">{{ optional($siswa->detail)->panggilan ?: '-' }}</dd>
                        </div>
                        <div class="rounded-xl bg-neutral-50 p-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-neutral-500">Tanggal lahir</dt>
                            <dd class="mt-2 text-sm font-medium text-neutral-900">{{ optional(optional($siswa->detail)->tgl_lahir)->format('Y-m-d') ?: '-' }}</dd>
                        </div>
                        <div class="rounded-xl bg-neutral-50 p-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-neutral-500">Umur</dt>
                            <dd class="mt-2 text-sm font-medium text-neutral-900">{{ optional(optional($siswa->detail)->tgl_lahir)->age ? optional(optional($siswa->detail)->tgl_lahir)->age . ' Tahun' : '-' }}</dd>
                        </div>
                        <div class="rounded-xl bg-neutral-50 p-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-neutral-500">Tempat lahir</dt>
                            <dd class="mt-2 text-sm font-medium text-neutral-900">{{ optional($siswa->detail)->tempat_lhr ?: '-' }}</dd>
                        </div>
                        <div class="rounded-xl bg-neutral-50 p-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-neutral-500">Status</dt>
                            <dd class="mt-2 text-sm font-medium capitalize text-neutral-900">{{ optional($siswa->detail)->pernikahan ?: '-' }}</dd>
                        </div>
                        <div class="rounded-xl bg-neutral-50 p-3 sm:col-span-2">
                            <dt class="text-xs font-medium uppercase tracking-wide text-neutral-500">Alamat</dt>
                            <dd class="mt-2 text-sm font-medium text-neutral-900">{{ optional($siswa->detail)->alamat ?: '-' }}</dd>
                        </div>
                        <div class="rounded-xl bg-neutral-50 p-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-neutral-500">No. telepon</dt>
                            <dd class="mt-2 text-sm font-medium text-neutral-900">{{ optional($siswa->detail)->wa ?: '-' }}</dd>
                        </div>
                        <div class="rounded-xl bg-neutral-50 p-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-neutral-500">No. wali</dt>
                            <dd class="mt-2 text-sm font-medium text-neutral-900">{{ optional($siswa->detail)->wa_wali ?: '-' }}</dd>
                        </div>
                    </dl>
                </section>

                <div class="space-y-4">
                    @if ($siswa->status === 'siswa')
                        <div class="rounded-2xl bg-white p-4">
                            <h2 class="text-base font-semibold text-neutral-900">Job yang sedang diikuti</h2>

                            @if (optional($siswa->list_w->first())->joblist)
                                @foreach ($siswa->list_w as $s)
                                    <div class="@if (!$loop->first) mt-3 border-t border-neutral-200 pt-3 @endif">
                                        <p class="text-sm font-medium text-neutral-900">{{ $s->joblist['nama_job'] }}</p>
                                        <p class="mt-1 text-sm text-neutral-600">Perusahaan: {{ $s->joblist['perusahaan'] }}</p>
                                        <p class="text-sm text-neutral-600">Tanggal: {{ $s->joblist['tgl_wawancara'] }}</p>
                                        <p class="text-sm text-neutral-600">Metode: {{ $s->joblist['metode'] }}</p>
                                    </div>
                                @endforeach
                            @else
                                <p class="mt-3 text-sm text-neutral-500">Belum mengikuti job.</p>
                            @endif
                        </div>
                    @elseif ($siswa->status === 'lolos')
                        <div class="rounded-2xl bg-white p-4">
                            <h2 class="text-base font-semibold text-neutral-900">Status kelulusan</h2>
                            <p class="mt-3 text-sm text-neutral-700">{{ optional($siswa->listlolos->detailso)->nama_so }}</p>
                            <p class="mt-1 text-sm text-neutral-600">Tgl lolos: {{ $siswa->listlolos->tgl_lolos }}</p>
                            <p class="text-sm text-neutral-600">Job: {{ $siswa->listlolos->nama_job }}</p>
                            <p class="text-sm text-neutral-600">Perusahaan: {{ $siswa->listlolos->nama_perusahaan }}</p>
                        </div>
                    @endif

                    <div class="rounded-2xl bg-white p-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="grow text-base font-semibold text-neutral-900">Daftar tagihan</p>
                            @if ($siswa->listtagihan_siswa)
                                <a href="{{ route('billing.statement', $siswa->nis) }}" class="text-sm font-medium text-red-700">Cetak Billing Statement</a>
                            @endif
                        </div>
                        <div class="mt-3 space-y-2">
                            @forelse($siswa->listtagihan_siswa as $tagihan)
                                <div class="flex items-center gap-3 rounded-xl bg-neutral-50 px-3 py-2.5">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-neutral-900">{{ $tagihan['nama_tagihan'] }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-wide text-neutral-500">{{ $tagihan['status_tagihan'] }}</p>
                                    </div>
                                    <p class="text-sm font-semibold text-neutral-800">
                                        @if ($tagihan['status_tagihan'] !== 'lunas')
                                            {{ number_format($tagihan['kekurangan_tagihan'], 0, '.', ',') }}
                                        @else
                                            <span class="text-emerald-700">Lunas</span>
                                        @endif
                                    </p>
                                </div>
                            @empty
                                <p class="text-sm text-neutral-500">Belum ada tagihan.</p>
                            @endforelse
                        </div>

                        <div class="mt-3 flex items-center border-t border-neutral-200 pt-3 text-sm font-semibold text-neutral-900">
                            <span class="grow">Total kekurangan</span>
                            <span>Rp. {{ number_format($this->totalTagihan, 0, '.', ',') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        @else

            <div class="flex h-full min-h-80 flex-col items-center justify-center bg-neutral-50 text-neutral-400">
                <svg class="mb-4 h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <p class="text-base font-medium text-neutral-600">Pilih siswa dari panel kiri</p>
                <p class="mt-1 text-sm text-neutral-400">Detail profil, tagihan, dan aksi siswa akan muncul di sini.</p>
            </div>

        @endif

    </div>
</div>
