@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 48px;
            border: 1px solid rgb(212 212 216);
            border-radius: 0.75rem;
            padding: 0.5rem 0.75rem;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: rgb(23 23 23);
            line-height: 1.25rem;
            padding-left: 0;
            padding-right: 1.75rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
            right: 0.75rem;
        }

        .select2-dropdown {
            border: 1px solid rgb(212 212 216);
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid rgb(212 212 216);
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
        }
    </style>
@endpush

<div class="space-y-6">
    <div class="flex flex-col gap-2">
        <h1 class="text-2xl font-bold text-neutral-900">Dokumen Siswa</h1>
    </div>

    <section class="grid gap-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
        <article class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm">
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-semibold text-neutral-900">Pilih siswa</p>
                    <p class="mt-1 text-sm text-neutral-500">Admin dapat memilih siswa dari daftar aktif yang tersedia di sistem.</p>
                </div>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-neutral-700">Siswa</span>
                    <div wire:ignore>
                        <select id="student-select"
                            data-selected-nis="{{ $selectedNis }}"
                            class="w-full rounded-xl border border-neutral-300 px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-red-900">
                            @forelse ($this->studentOptions as $student)
                                <option value="{{ $student['nis'] }}" @selected($selectedNis === $student['nis'])>
                                    {{ $student['nis'] }} - {{ $student['name'] }} ({{ $student['class'] }})
                                </option>
                            @empty
                                <option value="">Belum ada data siswa</option>
                            @endforelse
                        </select>
                    </div>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-neutral-700">Nama dokumen yang diambil</span>
                    <input type="text"
                        wire:model.live.debounce.300ms="customDocumentName"
                        class="w-full rounded-xl border border-neutral-300 px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-red-900"
                        placeholder="Contoh: Ijazah, Surat Tanah, Sertifikat" />
                    <p class="mt-2 text-xs text-neutral-500">Dipakai untuk surat pengambilan dokumen. Isi bebas sesuai kebutuhan admin.</p>
                </label>
            </div>
        </article>

        <article class="rounded-2xl border border-neutral-200 bg-gradient-to-br from-red-50 via-white to-amber-50 p-5 shadow-sm">
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-semibold text-neutral-900">Cetak dokumen</p>
                    <p class="mt-1 text-sm text-neutral-500">Setiap tombol akan membuka file PDF untuk siswa yang sedang dipilih.</p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach ([
                        ['type' => 'sp1', 'label' => 'Buat SP1', 'class' => 'bg-red-900 hover:bg-red-800'],
                        ['type' => 'sp2', 'label' => 'Buat SP2', 'class' => 'bg-red-900 hover:bg-red-800'],
                        ['type' => 'sp3', 'label' => 'Buat SP3', 'class' => 'bg-red-900 hover:bg-red-800'],
                        ['type' => 'cuti', 'label' => 'Surat Cuti', 'class' => 'bg-red-900 hover:bg-red-800'],
                        ['type' => 'rekomendasi-paspor', 'label' => 'Rekom Paspor', 'class' => 'bg-red-900 hover:bg-red-800'],
                    ] as $document)
                        @if ($selectedNis)
                            <a href="{{ route('documents.download', ['type' => $document['type'], 'nis' => $selectedNis]) }}"
                                target="_blank"
                                class="{{ $document['class'] }} inline-flex items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold text-white transition">
                                {{ $document['label'] }}
                            </a>
                        @else
                            <span class="inline-flex cursor-not-allowed items-center justify-center rounded-xl bg-neutral-300 px-4 py-3 text-sm font-semibold text-white">
                                {{ $document['label'] }}
                            </span>
                        @endif
                    @endforeach
                </div>

                <div class="rounded-2xl border border-neutral-200 bg-white/80 p-4">
                    <p class="text-sm font-semibold text-neutral-900">Surat pengambilan dokumen</p>
                    <p class="mt-1 text-sm text-neutral-500">Nama dokumen diambil dari input di panel kiri.</p>

                    <div class="mt-3">
                        @if ($selectedNis && filled(trim($customDocumentName)))
                            <a href="{{ route('documents.download', ['type' => 'pengambilan-dokumen', 'nis' => $selectedNis, 'document_name' => trim($customDocumentName)]) }}"
                                target="_blank"
                                class="inline-flex w-full items-center justify-center rounded-xl bg-red-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-800">
                                Buat Surat Pengambilan Dokumen
                            </a>
                        @else
                            <span class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-xl bg-neutral-300 px-4 py-3 text-sm font-semibold text-white">
                                Isi nama dokumen terlebih dahulu
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </article>
    </section>
</div>
