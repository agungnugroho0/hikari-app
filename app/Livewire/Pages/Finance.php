<?php

namespace App\Livewire\Pages;

use App\Models\Core;
use App\Models\DetailSiswa;
use App\Models\Tagihan;
use App\Models\Transaksi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Finance')]
class Finance extends Component
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'bulan', except: null)]
    public ?int $selectedMonth = null;

    #[Url(as: 'tahun', except: null)]
    public ?int $selectedYear = null;

    public int $perPage = 15;

    public function mount(): void
    {
        $this->selectedMonth ??= (int) now()->format('n');
        $this->selectedYear ??= (int) now()->format('Y');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedMonth(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedYear(): void
    {
        $this->resetPage();
    }

    public function setMonth(?int $month): void
    {
        $this->selectedMonth = $month;
        $this->resetPage();
    }

    #[Computed]
    public function monthOptions(): Collection
    {
        return collect(range(1, 12))->map(fn (int $month) => [
            'value' => $month,
            'label' => Carbon::create(now()->year, $month, 1)->translatedFormat('M'),
            'full_label' => Carbon::create(now()->year, $month, 1)->translatedFormat('F'),
        ]);
    }

    #[Computed]
    public function availableYears(): Collection
    {
        $tagihanYears = Tagihan::query()
            ->selectRaw('YEAR(tgl_terbit) as year')
            ->distinct()
            ->pluck('year');

        $transaksiYears = Transaksi::query()
            ->selectRaw('YEAR(tgl_transaksi) as year')
            ->distinct()
            ->pluck('year');

        $years = $tagihanYears
            ->merge($transaksiYears)
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
    public function students()
    {
        $search = trim($this->search);

        return Core::query()
            ->with([
                'detail',
                'listtagihan_siswa' => function ($query) use ($search) {
                    if ($search === '') {
                        $this->applyTagihanPeriod($query);
                    }

                    $query->orderBy('tgl_terbit')
                        ->orderBy('id_t');
                },
            ])
            ->when($search === '', function ($query) {
                $query->whereHas('listtagihan_siswa', fn ($subQuery) => $this->applyTagihanPeriod($subQuery));
            })
            ->when($search !== '', fn ($query) => $this->applyStudentSearch($query, $search))
            ->orderBy(
                DetailSiswa::query()
                    ->select('nama_lengkap')
                    ->whereColumn('detail_siswa.nis', 'core.nis')
                    ->limit(1)
            )
            ->paginate($this->perPage, pageName: 'finance-page');
    }

    #[Computed]
    public function summary(): array
    {
        $search = trim($this->search);

        $tagihanQuery = Tagihan::query();
        $studentQuery = Core::query();

        if ($search !== '') {
            $this->applyStudentSearch($studentQuery, $search);
            $tagihanQuery->whereIn('nis', (clone $studentQuery)->select('nis'));
        } else {
            $this->applyTagihanPeriod($tagihanQuery);
            $studentQuery->whereHas('listtagihan_siswa', fn ($query) => $this->applyTagihanPeriod($query));
        }

        $studentCount = (clone $studentQuery)->count();

        $totalTagihan = (int) (clone $tagihanQuery)->sum('total_tagihan');
        $totalKekurangan = (int) (clone $tagihanQuery)->sum('kekurangan_tagihan');
        $totalIncome = max(0, $totalTagihan - $totalKekurangan);

        return [
            'student_count' => $studentCount,
            'invoice_count' => (int) $tagihanQuery->count(),
            'total_tagihan' => $totalTagihan,
            'total_kekurangan' => $totalKekurangan,
            'total_income' => $totalIncome,
        ];
    }

    #[Computed]
    public function selectedPeriodLabel(): string
    {
        if (! $this->selectedMonth) {
            return 'Semua bulan '.$this->selectedYear;
        }

        return Carbon::create($this->selectedYear, $this->selectedMonth, 1)->translatedFormat('F Y');
    }

    protected function applyTagihanPeriod($query)
    {
        return $query
            ->when($this->selectedYear, fn ($builder) => $builder->whereYear('tgl_terbit', $this->selectedYear))
            ->when($this->selectedMonth, fn ($builder) => $builder->whereMonth('tgl_terbit', $this->selectedMonth));
    }

    protected function applyStudentSearch($query, string $search)
    {
        return $query->where(function ($subQuery) use ($search) {
            $subQuery->where('nis', 'like', "%{$search}%")
                ->orWhereHas('detail', function ($detailQuery) use ($search) {
                    $detailQuery->where('nama_lengkap', 'like', "%{$search}%");
                });
        });
    }

    public function render()
    {
        return view('pages.finance');
    }
}
