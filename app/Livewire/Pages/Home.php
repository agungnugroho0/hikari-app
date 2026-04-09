<?php
namespace App\Livewire\Pages;

use App\Models\Core;
use App\Models\ListLolos;
use App\Models\Staff;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.sensei')]
#[Title('Dashboard Guru')]
class Home extends Component
{
    public string $nama = '';

    public ?string $foto = null;

    public string $id_s = '';

    public ?Staff $sensei = null;

    public int $year;

    public ?string $selectedNis = null;

    public function mount(): void
    {
        $this->id_s = (string) Auth::user()->id_staff;
        $this->nama = (string) Auth::user()->nama_s;
        $this->foto = Auth::user()->foto_s;
        $this->sensei = Staff::with('kelas')->findOrFail($this->id_s);
        $this->year = $this->availableYears->first() ?? (int) now()->format('Y');
        $this->selectedNis = data_get($this->studentOptions->first(), 'nis');
    }

    #[Computed]
    public function availableYears(): Collection
    {
        $years = ListLolos::query()
            ->join('core', 'core.nis', '=', 'list_lolos.nis')
            ->where('core.id_kelas', $this->sensei?->kelas?->id_kelas)
            ->selectRaw('YEAR(list_lolos.tgl_lolos) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($year) => (int) $year);

        if ($years->isEmpty()) {
            return collect([(int) now()->format('Y')]);
        }

        return $years->values();
    }

    #[Computed]
    public function studentOptions(): Collection
    {
        return Core::query()
            ->with('detail')
            ->where('id_kelas', $this->sensei?->kelas?->id_kelas)
            ->where('status', 'siswa')
            ->orderBy('nis')
            ->get()
            ->map(function (Core $student): array {
                return [
                    'nis' => $student->nis,
                    'name' => $student->detail?->nama_lengkap ?? $student->detail?->panggilan ?? $student->nis,
                ];
            })
            ->values();
    }

    #[Computed]
    public function yearlyGraduationChart(): array
    {
        $rows = DB::table('list_lolos')
            ->join('core', 'core.nis', '=', 'list_lolos.nis')
            ->selectRaw('MONTH(list_lolos.tgl_lolos) as month_number, COUNT(DISTINCT list_lolos.nis) as total')
            ->where('core.id_kelas', $this->sensei?->kelas?->id_kelas)
            ->whereYear('list_lolos.tgl_lolos', $this->year)
            ->groupByRaw('MONTH(list_lolos.tgl_lolos)')
            ->orderByRaw('MONTH(list_lolos.tgl_lolos)')
            ->pluck('total', 'month_number');

        $series = collect(range(1, 12))
            ->map(fn (int $monthNumber) => [
                'month_number' => $monthNumber,
                'month_name' => now()->startOfYear()->month($monthNumber)->translatedFormat('M'),
                'total' => (int) ($rows[$monthNumber] ?? 0),
            ])
            ->all();

        $yearTotal = (int) DB::table('list_lolos')
            ->join('core', 'core.nis', '=', 'list_lolos.nis')
            ->where('core.id_kelas', $this->sensei?->kelas?->id_kelas)
            ->whereYear('list_lolos.tgl_lolos', $this->year)
            ->distinct('list_lolos.nis')
            ->count('list_lolos.nis');

        $peakMonth = collect($series)->sortByDesc('total')->first();

        return [
            'series' => $series,
            'year_total' => $yearTotal,
            'active_months' => collect($series)->filter(fn (array $item) => $item['total'] > 0)->count(),
            'peak_month' => $yearTotal > 0 ? ($peakMonth['month_name'] ?? '-') : '-',
        ];
    }

    public function getHomePayloadProperty(): array
    {
        return [
            'series' => $this->yearlyGraduationChart['series'],
            'year' => $this->year,
            'className' => $this->sensei?->kelas?->nama_kelas ?? '-',
        ];
    }

    public function rendered(): void
    {
        $this->dispatch('sensei-dashboard-chart-data', payload: $this->homePayload);
    }

    public function render()
    {
        return view('pages.home');
    }
};
