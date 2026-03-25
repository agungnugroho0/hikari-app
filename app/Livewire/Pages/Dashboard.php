<?php

namespace App\Livewire\Pages;

use App\Models\JobFairModels;
use App\Models\ListLolos;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public int $year;

    public function mount(): void
    {
        $this->year = $this->availableYears->first() ?? (int) now()->format('Y');
    }

    #[Computed]
    public function availableYears(): Collection
    {
        $years = ListLolos::query()
            ->selectRaw('YEAR(tgl_lolos) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($year) => (int) $year);

        if ($years->isEmpty()) {
            return collect([(int) now()->format('Y')]);
        }

        return $years;
    }

    #[Computed]
    public function yearlyGraduationChart(): array
    {
        $rows = DB::table('list_lolos')
            ->selectRaw('MONTH(tgl_lolos) as month_number, COUNT(DISTINCT nis) as total')
            ->whereYear('tgl_lolos', $this->year)
            ->groupByRaw('MONTH(tgl_lolos)')
            ->orderByRaw('MONTH(tgl_lolos)')
            ->pluck('total', 'month_number');

        $series = collect(range(1, 12))
            ->map(fn (int $monthNumber) => [
                'month_number' => $monthNumber,
                'month_name' => now()->startOfYear()->month($monthNumber)->translatedFormat('M'),
                'total' => (int) ($rows[$monthNumber] ?? 0),
            ])
            ->all();

        $yearTotal = (int) DB::table('list_lolos')
            ->whereYear('tgl_lolos', $this->year)
            ->distinct('nis')
            ->count('nis');

        $peakMonth = collect($series)
            ->sortByDesc('total')
            ->first();

        return [
            'series' => $series,
            'year_total' => $yearTotal,
            'active_months' => collect($series)->filter(fn (array $item) => $item['total'] > 0)->count(),
            'peak_month' => $yearTotal > 0 ? ($peakMonth['month_name'] ?? '-') : '-',
        ];
    }

    #[Computed]
    public function jobOrderEvents(): array
    {
        return JobFairModels::query()
            ->with('list_so:id_so,nama_so')
            ->whereNotNull('tgl_wawancara')
            ->orderBy('tgl_wawancara')
            ->orderBy('nama_job')
            ->get(['id_job', 'id_so', 'nama_job', 'perusahaan', 'tgl_wawancara'])
            ->map(fn (JobFairModels $job) => [
                'id_job' => $job->id_job,
                'sort_key' => Carbon::parse($job->tgl_wawancara)->toDateString(),
                'date' => Carbon::parse($job->tgl_wawancara)->translatedFormat('d M Y'),
                'from' => Carbon::parse($job->tgl_wawancara)->startOfDay()->toIso8601String(),
                'to' => Carbon::parse($job->tgl_wawancara)->endOfDay()->toIso8601String(),
                'title' => $job->nama_job,
                'company' => $job->perusahaan,
                'so_name' => $job->list_so?->nama_so,
            ])
            ->values()
            ->all();
    }

    #[Computed]
    public function upcomingJobOrders(): array
    {
        return collect($this->jobOrderEvents)
            ->sortBy('sort_key')
            ->take(6)
            ->values()
            ->all();
    }

    #[Computed]
    public function dashboardPayload(): array
    {
        return [
            'series' => $this->yearlyGraduationChart['series'],
            'year' => $this->year,
            'jobOrderEvents' => $this->jobOrderEvents,
        ];
    }

    public function rendered(): void
    {
        $this->dispatch('dashboard-yearly-chart-data', payload: $this->dashboardPayload);
    }

    public function render()
    {
        return view('pages.dashboard');
    }
}
