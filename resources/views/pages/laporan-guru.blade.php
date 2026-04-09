<div wire:key="sensei-report-{{ $year }}-{{ $month }}-{{ $selectedClassId }}" class="space-y-4 sm:space-y-5">
    <x-loading wire:loading wire:target="year,month,selectedClassId"></x-loading>

    <x-dashboard-header
        :foto="$foto"
        :nama="$nama"
        :kelas="$sensei?->kelas?->nama_kelas"
    />

    <x-menu-bar />

    <section class="grid gap-3 sm:grid-cols-3">
        <x-stat-card
            title="Tahun"
            :value="$year"
        />

        <x-stat-card
            title="Kelas"
            :value="$sensei?->kelas?->nama_kelas ?? '-'"
            description="Laporan dibatasi untuk kelas yang Anda ampu"
        />

        <x-stat-card
            title="Total Lulus"
            :value="$this->totalGraduationChart['year_total']"
            :description="'Rekap siswa lulus pada tahun ' . $year"
        />
    </section>

    <section class="rounded-[28px] border border-neutral-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-900/70">Laporan Guru</p>
                <h1 class="mt-1 text-2xl font-bold text-neutral-900">Laporan kelas {{ $sensei?->kelas?->nama_kelas ?? '-' }}</h1>
                <p class="mt-1 text-sm text-neutral-500">Isi laporan mengikuti halaman admin, tetapi hanya menampilkan data kelas yang Anda ampu.</p>
            </div>

            <div class="grid gap-3 md:grid-cols-3 xl:grid-cols-4">
                <label class="flex flex-col gap-1 text-sm">
                    <span class="font-medium text-neutral-700">Tahun grafik</span>
                    <select wire:model.live="year" class="rounded-2xl border border-neutral-300 px-4 py-3 text-sm text-neutral-900">
                        @foreach ($this->availableYears as $availableYear)
                            <option value="{{ $availableYear }}">{{ $availableYear }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="flex flex-col gap-1 text-sm">
                    <span class="font-medium text-neutral-700">Kelas</span>
                    <input
                        type="text"
                        value="{{ $this->classGraduationChart['class_name'] }}"
                        readonly
                        class="rounded-2xl border border-neutral-300 bg-neutral-50 px-4 py-3 text-sm text-neutral-700"
                    >
                </label>

                <label class="flex flex-col gap-1 text-sm">
                    <span class="font-medium text-neutral-700">Bulan</span>
                    <select wire:model.live="month" class="rounded-2xl border border-neutral-300 px-4 py-3 text-sm text-neutral-900">
                        @for ($monthNumber = 1; $monthNumber <= 12; $monthNumber++)
                            <option value="{{ $monthNumber }}">
                                {{ now()->startOfYear()->month($monthNumber)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </label>

                <div class="flex flex-col gap-1 text-sm">
                    <span class="font-medium text-neutral-700">Export</span>
                    <div class="grid gap-2">
                        <a href="{{ route('sensei.reports.monthly-score', ['year' => $year, 'month' => $month]) }}"
                            class="inline-flex min-h-11 items-center justify-center rounded-2xl bg-red-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-800">
                            Cetak Formulir Nilai
                        </a>
                        <a href="{{ route('sensei.reports.attendance', ['year' => $year, 'month' => $month, 'class_id' => $selectedClassId]) }}"
                            class="inline-flex min-h-11 items-center justify-center rounded-2xl bg-emerald-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600">
                            Export Absensi Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($this->attendanceChart['recap'] as $attendanceRecap)
            <article class="rounded-[24px] border border-neutral-200 bg-white p-4 shadow-sm">
                <p class="text-sm text-neutral-500">{{ $attendanceRecap['label'] }}</p>
                <p class="mt-2 text-3xl font-bold text-neutral-900">{{ number_format($attendanceRecap['percentage'], 1) }}%</p>
                <div class="mt-3 h-2 rounded-full" style="background: {{ $attendanceRecap['background'] }}">
                    <div class="h-2 rounded-full" style="width: {{ min(100, $attendanceRecap['percentage']) }}%; background: {{ $attendanceRecap['color'] }}"></div>
                </div>
            </article>
        @endforeach
    </section>

    <section class="rounded-[28px] border border-neutral-200 bg-white p-5 shadow-sm">
        <div class="mb-5">
            <h2 class="text-lg font-semibold text-neutral-900">Grafik Kelulusan</h2>
        </div>

        <div class="grid gap-4 xl:grid-cols-2">
            <article class="rounded-3xl bg-neutral-50 p-4">
                <div class="mb-4 flex items-baseline justify-between">
                    <div>
                        <h3 class="font-semibold text-neutral-900">Semua kelas yang diampu</h3>
                        <p class="text-sm text-neutral-500">Rekapan jumlah lulusan per bulan selama {{ $year }}</p>
                    </div>
                    <p class="text-sm font-medium text-neutral-700">{{ $this->totalGraduationChart['year_total'] }} siswa</p>
                </div>

                <div class="h-64" wire:ignore>
                    <canvas id="total-graduation-chart"></canvas>
                </div>
            </article>

            <article class="rounded-3xl bg-neutral-50 p-4">
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

    <section class="rounded-[28px] border border-neutral-200 bg-white p-5 shadow-sm">
        <div class="grid gap-3 xl:grid-cols-2">
            <article class="rounded-3xl bg-neutral-50 p-4">
                <div class="mb-4">
                    <p class="font-semibold text-neutral-900">Rekap Absensi Harian</p>
                    <p class="text-sm text-neutral-500">Jumlah siswa per status setiap hari.</p>
                </div>

                <div class="h-72" wire:ignore>
                    <canvas id="class-attendance-chart"></canvas>
                </div>
            </article>

            <article class="rounded-3xl bg-neutral-50 p-4">
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
