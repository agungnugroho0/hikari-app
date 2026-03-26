<?php

namespace App\Livewire\Pages;

use App\Models\Absen;
use App\Models\Kelas;
use App\Models\ListLolos;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Laporan')]
class Laporan extends Component
{
    protected const ATTENDANCE_STATUSES = [
        'hadir' => [
            'label' => 'Hadir',
            'color' => '#166534',
            'background' => 'rgba(34, 197, 94, 0.18)',
        ],
        'mensetsu' => [
            'label' => 'Mensetsu',
            'color' => '#1d4ed8',
            'background' => 'rgba(59, 130, 246, 0.18)',
        ],
        'ijin' => [
            'label' => 'Ijin',
            'color' => '#b45309',
            'background' => 'rgba(245, 158, 11, 0.18)',
        ],
        'alfa' => [
            'label' => 'Alfa',
            'color' => '#991b1b',
            'background' => 'rgba(239, 68, 68, 0.18)',
        ],
    ];

    public int $year;

    public int $month;

    public ?int $selectedClassId = null;

    public function mount(): void
    {
        $this->year = $this->availableYears->first() ?? (int) now()->format('Y');
        $this->month = (int) now()->format('n');
        $this->selectedClassId = data_get($this->classOptions->first(), 'id');
    }

    #[Computed]
    public function availableYears(): Collection
    {
        $graduationYears = ListLolos::query()
            ->selectRaw('YEAR(tgl_lolos) as year')
            ->distinct()
            ->pluck('year');

        $attendanceYears = Absen::query()
            ->selectRaw('YEAR(tgl) as year')
            ->distinct()
            ->pluck('year');

        $years = $graduationYears
            ->merge($attendanceYears)
            ->filter()
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->sortDesc()
            ->values();

        if ($years->isEmpty()) {
            return collect([(int) now()->format('Y')]);
        }

        return $years;
    }

    #[Computed]
    public function classOptions(): Collection
    {
        return Kelas::query()
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get(['id_kelas', 'nama_kelas'])
            ->map(fn (Kelas $kelas) => [
                'id' => (int) $kelas->id_kelas,
                'name' => $kelas->nama_kelas,
            ]);
    }

    #[Computed]
    public function totalGraduationChart(): array
    {
        $rows = DB::table('list_lolos')
            ->selectRaw('MONTH(tgl_lolos) as month_number, COUNT(DISTINCT nis) as total')
            ->whereYear('tgl_lolos', $this->year)
            ->groupByRaw('MONTH(tgl_lolos)')
            ->orderByRaw('MONTH(tgl_lolos)')
            ->pluck('total', 'month_number');

        $yearTotal = (int) DB::table('list_lolos')
            ->whereYear('tgl_lolos', $this->year)
            ->distinct('nis')
            ->count('nis');

        $series = $this->buildMonthlySeries($rows);

        return [
            'series' => $series,
            'year_total' => $yearTotal,
            'max_total' => max(1, max(array_column($series, 'total'))),
            'line_points' => $this->buildLinePoints($series),
            'area_points' => $this->buildAreaPoints($series),
        ];
    }

    #[Computed]
    public function classGraduationChart(): array
    {
        $startDate = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $rows = DB::table('list_lolos')
            ->join('core', 'core.nis', '=', 'list_lolos.nis')
            ->selectRaw('DAY(list_lolos.tgl_lolos) as day_number, COUNT(DISTINCT list_lolos.nis) as total')
            ->whereBetween('list_lolos.tgl_lolos', [$startDate->toDateString(), $endDate->toDateString()])
            ->when($this->selectedClassId, fn ($query) => $query->where('core.id_kelas', $this->selectedClassId))
            ->groupByRaw('DAY(list_lolos.tgl_lolos)')
            ->orderByRaw('DAY(list_lolos.tgl_lolos)')
            ->pluck('total', 'day_number');

        $monthTotal = (int) DB::table('list_lolos')
            ->join('core', 'core.nis', '=', 'list_lolos.nis')
            ->whereBetween('list_lolos.tgl_lolos', [$startDate->toDateString(), $endDate->toDateString()])
            ->when($this->selectedClassId, fn ($query) => $query->where('core.id_kelas', $this->selectedClassId))
            ->distinct('list_lolos.nis')
            ->count('list_lolos.nis');

        $series = $this->buildDailySeries($startDate, $rows);
        $selectedClassName = data_get(
            $this->classOptions->firstWhere('id', $this->selectedClassId),
            'name',
            'Semua Kelas'
        );

        return [
            'series' => $series,
            'class_name' => $selectedClassName,
            'year_total' => $monthTotal,
            'max_total' => max(1, max(array_column($series, 'total'))),
            'selected_month_name' => now()->startOfYear()->month($this->month)->translatedFormat('F'),
            'line_points' => $this->buildLinePoints($series),
            'area_points' => $this->buildAreaPoints($series),
        ];
    }

    #[Computed]
    public function attendanceChart(): array
    {
        $startDate = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $rows = DB::table('absen')
            ->join('core', 'core.nis', '=', 'absen.nis')
            ->selectRaw("
                DAY(absen.tgl) as day_number,
                CASE
                    WHEN LOWER(absen.ket) = 'h' THEN 'hadir'
                    WHEN LOWER(absen.ket) = 'i' THEN 'ijin'
                    WHEN LOWER(absen.ket) = 'm' THEN 'mensetsu'
                    WHEN LOWER(absen.ket) = 'a' THEN 'alfa'
                    ELSE 'unknown'
                END as status_key,
                COUNT(*) as total
            ")
            ->whereBetween('absen.tgl', [$startDate->toDateString(), $endDate->toDateString()])
            ->when($this->selectedClassId, fn ($query) => $query->where('core.id_kelas', $this->selectedClassId))
            ->groupByRaw("
                DAY(absen.tgl),
                CASE
                    WHEN LOWER(absen.ket) = 'h' THEN 'hadir'
                    WHEN LOWER(absen.ket) = 'i' THEN 'ijin'
                    WHEN LOWER(absen.ket) = 'm' THEN 'mensetsu'
                    WHEN LOWER(absen.ket) = 'a' THEN 'alfa'
                    ELSE 'unknown'
                END
            ")
            ->orderByRaw('DAY(absen.tgl)')
            ->get();

        $groupedRows = $rows
            ->groupBy('day_number')
            ->map(fn (Collection $items) => $items->pluck('total', 'status_key'));

        $series = collect(range(1, $startDate->daysInMonth))
            ->map(function (int $dayNumber) use ($groupedRows, $startDate) {
                $totals = $groupedRows->get($dayNumber, collect());

                return [
                    'day_number' => $dayNumber,
                    'label' => str_pad((string) $dayNumber, 2, '0', STR_PAD_LEFT),
                    'full_date' => $startDate->copy()->day($dayNumber)->format('d-m-Y'),
                    'hadir' => (int) ($totals['hadir'] ?? 0),
                    'mensetsu' => (int) ($totals['mensetsu'] ?? 0),
                    'ijin' => (int) ($totals['ijin'] ?? 0),
                    'alfa' => (int) ($totals['alfa'] ?? 0),
                ];
            })
            ->all();

        $recap = collect(self::ATTENDANCE_STATUSES)
            ->map(function (array $status, string $key) use ($rows) {
                return [
                    'key' => $key,
                    'label' => $status['label'],
                    'color' => $status['color'],
                    'background' => $status['background'],
                    'total' => (int) $rows->where('status_key', $key)->sum('total'),
                ];
            })
            ->values()
            ->all();

        return [
            'series' => $series,
            'recap' => $recap,
            'class_name' => data_get(
                $this->classOptions->firstWhere('id', $this->selectedClassId),
                'name',
                'Semua Kelas'
            ),
            'selected_month_name' => $startDate->translatedFormat('F'),
            'total_records' => array_sum(array_column($recap, 'total')),
            'max_total' => max(1, ...array_map(fn (array $item) => max($item['hadir'], $item['mensetsu'], $item['ijin'], $item['alfa']), $series)),
        ];
    }

    protected function buildMonthlySeries(Collection $rows): array
    {
        return collect(range(1, 12))
            ->map(fn (int $monthNumber) => [
                'month_number' => $monthNumber,
                'month_name' => now()->startOfYear()->month($monthNumber)->translatedFormat('M'),
                'total' => (int) ($rows[$monthNumber] ?? 0),
            ])
            ->all();
    }

    protected function buildDailySeries(Carbon $startDate, Collection $rows): array
    {
        $daysInMonth = $startDate->daysInMonth;

        return collect(range(1, $daysInMonth))
            ->map(fn (int $dayNumber) => [
                'day_number' => $dayNumber,
                'label' => str_pad((string) $dayNumber, 2, '0', STR_PAD_LEFT),
                'full_date' => $startDate->copy()->day($dayNumber)->format('d-m-Y'),
                'total' => (int) ($rows[$dayNumber] ?? 0),
            ])
            ->all();
    }

    protected function buildLinePoints(array $series): string
    {
        $maxValue = max(1, max(array_column($series, 'total')));
        $lastIndex = max(1, count($series) - 1);

        return collect($series)
            ->map(function (array $item, int $index) use ($maxValue, $lastIndex) {
                $x = 24 + (($index / $lastIndex) * 312);
                $y = 176 - (($item['total'] / $maxValue) * 140);

                return round($x, 2).','.round($y, 2);
            })
            ->implode(' ');
    }

    protected function buildAreaPoints(array $series): string
    {
        return '24,176 '.$this->buildLinePoints($series).' 336,176';
    }

    protected function chartPayload(): array
    {
        return [
            'totalSeries' => $this->totalGraduationChart['series'],
            'classSeries' => $this->classGraduationChart['series'],
            'attendanceSeries' => $this->attendanceChart['series'],
            'attendanceRecap' => $this->attendanceChart['recap'],
            'year' => $this->year,
            'className' => $this->classGraduationChart['class_name'],
            'monthName' => $this->classGraduationChart['selected_month_name'],
            'attendanceClassName' => $this->attendanceChart['class_name'],
            'attendanceMonthName' => $this->attendanceChart['selected_month_name'],
        ];
    }

    public function rendered(): void
    {
        $this->dispatch('report-charts-data', payload: $this->chartPayload());
    }

    public function render()
    {
        return view('pages.laporan');
    }
}
