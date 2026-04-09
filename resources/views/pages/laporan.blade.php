<div wire:key="report-charts-{{ $year }}-{{ $month }}-{{ $selectedClassId }}" class="space-y-6">
    <x-loading wire:loading wire:target="year,month,selectedClassId"></x-loading>

    <div class="flex flex-col gap-3 lg:flex-row lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900">Laporan</h1>
            <p class="text-sm text-neutral-600">Grafik kelulusan, grafik absensi per kelas per bulan, dan export laporan.</p>
        </div>

        <div class="grid gap-3 md:grid-cols-3 xl:grid-cols-4">
            <label class="flex flex-col gap-1 text-sm">
                <span class="font-medium text-neutral-700">Tahun grafik</span>
                <select wire:model.live="year" class="rounded border border-neutral-300 px-3 py-2 text-sm">
                    @foreach ($this->availableYears as $availableYear)
                        <option value="{{ $availableYear }}">{{ $availableYear }}</option>
                    @endforeach
                </select>
            </label>

            <label class="flex flex-col gap-1 text-sm">
                <span class="font-medium text-neutral-700">Pilih kelas</span>
                <select wire:model.live="selectedClassId" class="rounded border border-neutral-300 px-3 py-2 text-sm">
                    @foreach ($this->classOptions as $classOption)
                        <option value="{{ $classOption['id'] }}">{{ $classOption['name'] }}</option>
                    @endforeach
                </select>
            </label>

            <label class="flex flex-col gap-1 text-sm">
                <span class="font-medium text-neutral-700">Bulan</span>
                <select wire:model.live="month" class="rounded border border-neutral-300 px-3 py-2 text-sm">
                    @for ($monthNumber = 1; $monthNumber <= 12; $monthNumber++)
                        <option value="{{ $monthNumber }}">
                            {{ now()->startOfYear()->month($monthNumber)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </label>

            <div class="flex flex-col gap-1 text-sm xl:col-span-1">
                <span class="font-medium text-neutral-700">Export</span>
                <div class="grid gap-2">
                    <a href="{{ route('reports.monthly-score', ['year' => $year, 'month' => $month]) }}"
                        class="inline-flex items-center justify-center rounded bg-red-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-800">
                        Cetak Formulir Nilai
                    </a>
                    <a href="{{ route('reports.attendance', ['year' => $year, 'month' => $month, 'class_id' => $selectedClassId]) }}"
                        class="inline-flex items-center justify-center rounded bg-emerald-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600">
                        Export Absensi Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-lg border border-neutral-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-neutral-500">Tahun</p>
            <p class="mt-2 text-3xl font-bold text-neutral-900">{{ $year }}</p>
        </div>
        <div class="rounded-lg border border-neutral-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-neutral-500">Total kelulusan</p>
            <p class="mt-2 text-3xl font-bold text-neutral-900">{{ $this->totalGraduationChart['year_total'] }}</p>
        </div>
        <div class="rounded-lg border border-neutral-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-neutral-500">Bulan </p>
            <p class="mt-2 text-3xl font-bold text-neutral-900">
                {{ now()->startOfYear()->month($month)->translatedFormat('F') }}
            </p>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($this->attendanceChart['recap'] as $attendanceRecap)
            <article class="rounded-lg border border-neutral-200 bg-white p-4 shadow-sm">
                <p class="text-sm text-neutral-500">{{ $attendanceRecap['label'] }}</p>
                <p class="mt-2 text-3xl font-bold text-neutral-900">{{ number_format($attendanceRecap['percentage'], 1) }}%</p>
                <div class="mt-3 h-2 rounded-full" style="background: {{ $attendanceRecap['background'] }}">
                    <div class="h-2 rounded-full" style="width: {{ min(100, $attendanceRecap['percentage']) }}%; background: {{ $attendanceRecap['color'] }}"></div>
                </div>
            </article>
        @endforeach
    </section>

    <section class="rounded-xl  border-neutral-200 bg-white p-5 shadow-sm">
        <div class="mb-5">
            <div>
                <h2 class="text-lg font-semibold text-neutral-900">Grafik Kelulusan</h2>
                {{-- <p class="text-sm text-neutral-500">Semua grafik berbentuk line chart berdasarkan data `list_lolos`.</p> --}}
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-2">
            <article class="rounded-lg border-neutral-200 bg-neutral-50 p-4">
                <div class="mb-4 flex items-baseline justify-between">
                    <div>
                        <h3 class="font-semibold text-neutral-900">Semua Kelas</h3>
                        <p class="text-sm text-neutral-500">Rekapan jumlah lulusan per bulan selama {{ $year }}</p>
                    </div>
                    <p class="text-sm font-medium text-neutral-700">{{ $this->totalGraduationChart['year_total'] }} siswa</p>
                </div>

                <div class="h-64" wire:ignore>
                    <canvas id="total-graduation-chart"></canvas>
                </div>
            </article>

            <article class="rounded-lg border-neutral-200 bg-neutral-50 p-4">
                <div class="mb-4 flex items-baseline justify-between">
                    <div>
                        <h3 class="font-semibold text-neutral-900">Kelas {{ $this->classGraduationChart['class_name'] }}</h3>
                        <p class="text-sm text-neutral-500">Jumlah kelulusan siswa per tanggal pada bulan {{ $this->classGraduationChart['selected_month_name'] }} {{ $year }}</p>
                    </div>
                    <p class="text-sm font-medium text-neutral-700">{{ $this->classGraduationChart['year_total'] }} siswa</p>
                </div>

                <div class="h-64" wire:ignore>
                    <canvas id="class-graduation-chart"></canvas>
                </div>
            </article>
        </div>
    </section>

    <section class=" border-neutral-200 bg-white p-5 shadow-sm">
        <div class="mb-5 flex items-baseline justify-between gap-4">
            <div>
                {{-- <h2 class="text-lg font-semibold text-neutral-900">Grafik Absensi</h2> --}}
                {{-- <p class="text-sm text-neutral-500">
                    Rekap absensi harian untuk {{ $this->attendanceChart['class_name'] }} pada bulan
                    {{ $this->attendanceChart['selected_month_name'] }} {{ $year }}.
                </p> --}}
            </div>
            {{-- <p class="text-sm font-medium text-neutral-700">{{ $this->attendanceChart['total_records'] }} catatan</p> --}}
        </div>

        <div class="grid gap-3 xl:grid-cols-2">
            <article class=" border-neutral-200 bg-neutral-50 p-4">
                <div class="mb-4">
                    {{-- <h3 class="font-semibold text-neutral-900">{{ $this->attendanceChart['class_name'] }}</h3> --}}
                    <p class="font-semibold text-neutral-900">Rekap Absensi Harian</p>
                    <p class="text-sm text-neutral-500">Jumlah siswa per status setiap hari.</p>
                </div>

                <div class="h-72" wire:ignore>
                    <canvas id="class-attendance-chart"></canvas>
                </div>
            </article>

            <article class=" border-neutral-200 bg-neutral-50 p-4">
                <div class="mb-4">
                    <h3 class="font-semibold text-neutral-900">Rekap Bulanan</h3>
                    <p class="text-sm text-neutral-500">Persentase status selama 1 bulan dari total catatan absensi yang tercatat.</p>
                </div>

                <div class="h-72" wire:ignore>
                    <canvas id="attendance-recap-chart"></canvas>
                </div>
            </article>
        </div>
    </section>
</div>
