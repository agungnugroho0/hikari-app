@php
    $students = $this->students;
@endphp

<div class="space-y-6">
    <x-loading wire:loading wire:target="search,selectedMonth,selectedYear,setMonth,gotoPage,nextPage,previousPage"></x-loading>

    <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900">Finance</h1>
            <p class="text-sm text-neutral-600">Ringkasan pendapatan bulanan, dan cetak billing statement.</p>
        </div>

        <div class="grid gap-3 md:grid-cols-[minmax(0,20rem)_12rem]">
            <label class="flex flex-col gap-1 text-sm">
                <span class="font-medium text-neutral-700">Cari siswa</span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari semua siswa berdasarkan nama atau NIS"
                    class="rounded-xl border border-neutral-300 px-3 py-2 text-sm focus:border-red-700 focus:outline-none"
                >
            </label>

            <label class="flex flex-col gap-1 text-sm">
                <span class="font-medium text-neutral-700">Tahun</span>
                <select wire:model.live="selectedYear" class="rounded-xl border border-neutral-300 px-3 py-2 text-sm focus:border-red-700 focus:outline-none">
                    @foreach ($this->availableYears as $yearOption)
                        <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                    @endforeach
                </select>
            </label>
        </div>
    </div>

    <section class="rounded-2xl border border-neutral-200 bg-white p-4 shadow-sm">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-neutral-400">Filter bulan</p>
                <h2 class="mt-1 text-base font-semibold text-neutral-900">{{ $this->selectedPeriodLabel }}</h2>
            </div>

            <div class="flex flex-wrap gap-2">
                @foreach ($this->monthOptions as $monthOption)
                    <button
                        type="button"
                        wire:click="setMonth({{ $monthOption['value'] }})"
                        class="{{ $selectedMonth === $monthOption['value'] ? 'bg-red-900 text-white' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200' }} rounded-full px-3 py-2 text-sm font-medium transition"
                    >
                        {{ $monthOption['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-2xl border border-neutral-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-neutral-500">Total income</p>
            <p class="mt-2 text-3xl font-bold text-neutral-900">Rp {{ number_format($this->summary['total_income'], 0, ',', '.') }}</p>
            <p class="mt-2 text-xs text-neutral-500">Akumulasi pembayaran dari tagihan pada {{ strtolower($this->selectedPeriodLabel) }}</p>
        </article>

        <article class="rounded-2xl border border-neutral-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-neutral-500">Total siswa</p>
            <p class="mt-2 text-3xl font-bold text-neutral-900">{{ number_format($this->summary['student_count'], 0, ',', '.') }}</p>
            <p class="mt-2 text-xs text-neutral-500">Siswa dengan tagihan pada periode ini</p>
        </article>

        <article class="rounded-2xl border border-neutral-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-neutral-500">Total tagihan</p>
            <p class="mt-2 text-3xl font-bold text-neutral-900">Rp {{ number_format($this->summary['total_tagihan'], 0, ',', '.') }}</p>
            <p class="mt-2 text-xs text-neutral-500">{{ number_format($this->summary['invoice_count'], 0, ',', '.') }} invoice</p>
        </article>

        <article class="rounded-2xl border border-neutral-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-neutral-500">Total kekurangan</p>
            <p class="mt-2 text-3xl font-bold text-neutral-900">Rp {{ number_format($this->summary['total_kekurangan'], 0, ',', '.') }}</p>
            <p class="mt-2 text-xs text-neutral-500">Sisa tagihan seluruh siswa</p>
        </article>
    </section>

    <section class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 text-sm">
                <colgroup>
                    <col class="w-16">
                    <col class="w-[26%]">
                    <col class="w-[24%]">
                    <col class="w-[14%]">
                    <col class="w-[14%]">
                    <col class="w-[12%]">
                    <col class="w-[10%]">
                </colgroup>
                <thead class="bg-neutral-50 text-left text-neutral-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">No</th>
                        <th class="px-4 py-3 font-semibold">No Invoice</th>
                        <th class="px-4 py-3 font-semibold">Nama Lengkap</th>
                        <th class="px-4 py-3 font-semibold">Total Tagihan</th>
                        <th class="px-4 py-3 font-semibold">Total Kekurangan</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Billing Statement</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse ($students as $student)
                        @php
                            $tagihanList = $student->listtagihan_siswa;
                            $totalTagihan = (int) $tagihanList->sum('total_tagihan');
                            $totalKekurangan = (int) $tagihanList->sum('kekurangan_tagihan');
                            $isLunas = $tagihanList->isNotEmpty() && $tagihanList->every(function ($tagihan) {
                                return strtolower((string) $tagihan->status_tagihan) === 'lunas';
                            });
                        @endphp
                        <tr wire:key="finance-row-{{ $student->nis }}" class="align-middle">
                            <td class="whitespace-nowrap px-4 py-4 text-neutral-700">
                                {{ number_format($students->firstItem() + $loop->index, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @forelse ($tagihanList as $tagihan)
                                        <span class="whitespace-nowrap rounded-full bg-neutral-100 px-2.5 py-1 text-xs font-medium text-neutral-700">
                                            {{ $tagihan->id_t }}
                                        </span>
                                    @empty
                                        <span class="whitespace-nowrap rounded-full bg-neutral-100 px-2.5 py-1 text-xs font-medium text-neutral-500">
                                            Tidak ada tagihan
                                        </span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <p class="whitespace-nowrap font-semibold uppercase text-neutral-900">{{ data_get($student, 'detail.nama_lengkap', '-') }}</p>
                                <p class="mt-1 text-xs text-neutral-500">NIS {{ $student->nis }}</p>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 font-medium text-neutral-800">
                                Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 font-medium {{ $totalKekurangan > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                                Rp {{ number_format($totalKekurangan, 0, ',', '.') }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                <span class="{{ $tagihanList->isEmpty() ? 'bg-neutral-100 text-neutral-600' : ($isLunas ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700') }} inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide">
                                    {{ $tagihanList->isEmpty() ? 'Tidak Ada Tagihan' : ($isLunas ? 'Lunas' : 'Belum Lunas') }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                <a href="{{ route('billing.statement', $student->nis) }}" class="text-sm font-semibold text-red-800 transition hover:text-red-600 hover:underline">
                                    Cetak
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-neutral-500">
                                Data finance siswa pada periode ini belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-neutral-200 px-4 py-4">
            {{ $students->links('components.pagination.student-list') }}
        </div>
    </section>
</div>
