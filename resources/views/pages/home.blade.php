@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
@endpush

<div wire:key="sensei-home-{{ $year }}-{{ $selectedNis }}" class="space-y-4 sm:space-y-5">
    <x-dashboard-header
        :foto="$foto"
        :nama="$nama"
        :kelas="$sensei?->kelas?->nama_kelas"
    />

    <x-menu-bar></x-menu-bar>

    <section class="grid gap-3 sm:grid-cols-3">
        <x-stat-card
            title="Tahun"
            :value="$year"
        />

        <x-stat-card
            title="Total Lulus"
            :value="$this->yearlyGraduationChart['year_total']"
        />

        <x-stat-card
            title="Jumlah Siswa Kelas"
            :value="$this->studentOptions->count()"
            :description="'Siswa aktif di kelas ' . ($sensei?->kelas?->nama_kelas ?? '-')"
        />
    </section>

    <section class="grid gap-4 xl:grid-cols-[minmax(0,1.35fr)_minmax(0,0.85fr)]">
        <article class="rounded-[28px] border border-neutral-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Grafik Kelulusan</h2>
                    <p class="text-sm text-neutral-500">Kelulusan siswa per bulan untuk kelas yang Anda ampu.</p>
                </div>

                <label class="flex w-full flex-col gap-1 text-sm sm:w-40">
                    <span class="font-medium text-neutral-700">Tahun</span>
                    <select wire:model.live="year" class="rounded-2xl border border-neutral-300 px-4 py-3 text-sm text-neutral-900">
                        @foreach ($this->availableYears as $availableYear)
                            <option value="{{ $availableYear }}">{{ $availableYear }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="mt-5 rounded-3xl bg-neutral-50 p-3 sm:p-4">
                <div class="h-64 sm:h-72" wire:ignore>
                    <canvas id="sensei-graduation-chart"></canvas>
                </div>
            </div>
        </article>

        <article class="rounded-[28px] border border-neutral-200 bg-white p-4 shadow-sm sm:p-5">
            <div>
                <h2 class="text-lg font-semibold text-neutral-900">Pembuatan SP</h2>
                <p class="mt-1 text-sm text-neutral-500">Pilih siswa lalu cetak SP1 atau SP2 dengan cepat.</p>
            </div>

            <div class="mt-5 space-y-4">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-neutral-700">Siswa</span>
                    <div wire:ignore class="sensei-student-select-wrap">
                        <select id="sensei-student-select"
                            data-selected-nis="{{ $selectedNis }}"
                            class="w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm text-neutral-900">
                            <option value="">Pilih siswa</option>
                            @foreach ($this->studentOptions as $student)
                                <option value="{{ $student['nis'] }}" @selected($selectedNis === $student['nis'])>
                                    {{ $student['nis'] }} - {{ $student['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </label>

                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach ([
                        ['type' => 'sp1', 'label' => 'Buat SP1'],
                        ['type' => 'sp2', 'label' => 'Buat SP2'],
                    ] as $document)
                        @if ($selectedNis)
                            <a href="{{ route('sensei.documents.download', ['type' => $document['type'], 'nis' => $selectedNis]) }}"
                                target="_blank"
                                class="inline-flex min-h-12 items-center justify-center rounded-2xl bg-red-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-800">
                                {{ $document['label'] }}
                            </a>
                        @else
                            <span class="inline-flex min-h-12 items-center justify-center rounded-2xl bg-neutral-200 px-4 py-3 text-sm font-semibold text-neutral-500">
                                {{ $document['label'] }}
                            </span>
                        @endif
                    @endforeach
                </div>

                <div class="rounded-3xl bg-red-50 px-4 py-3 text-sm text-red-900">
                    SP hanya tersedia untuk siswa di kelas Anda dan dibatasi pada SP1 serta SP2.
                </div>
            </div>
        </article>
    </section>

    <script type="application/json" id="sensei-home-chart-payload">@json($this->homePayload)</script>
</div>
