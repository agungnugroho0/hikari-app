<?php

use Livewire\Component;
use App\Models\Core;
use Livewire\Attributes\Reactive;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Livewire\Detailsiswa;
use App\Services\SiswaServices;
use Livewire\Attributes\On;

new class extends Component {
    use WithPagination;
    use WithoutUrlPagination;

    #[Reactive]
    public $idKelas;

    #[Reactive]
    public $search;

    public $status = 'siswa';
    public $selectedNis = null;

    #[Computed]
    public function Siswa()
    {
        $query = Core::query()
            ->with(['detail', 'list_w'])
            ->where('status', '!=', 'dihapus');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nis', 'like', "%{$this->search}%")->orWhereHas('detail', function ($sub) {
                    $sub->where('nama_lengkap', 'like', "%{$this->search}%");
                });
            });
        } else {
            if ($this->idKelas) {
                $query->where('id_kelas', (int) $this->idKelas);
            }
            $query->where('status', '=', $this->status);
        }

        return $query->latest()->paginate(25);
    }

    #[On('siswa-updated')]
    public function refreshList()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedIdKelas()
    {
        $this->resetPage();
    }

    public function pilihSiswa($nis)
    {
        $this->selectedNis = $nis;
        $this->dispatch('pilih-siswa', nis: $nis)->to(Detailsiswa::class);
        $this->dispatch('pilih-siswa', nis: $nis);
    }

    public function hapusSiswa(string $nis, SiswaServices $service)
    {
        $service->delete($nis);

        if ($this->selectedNis === $nis) {
            $this->selectedNis = null;
        }

        $this->resetPage();
        $this->dispatch('siswa-deleted', nis: $nis)->to(Detailsiswa::class);
        $this->dispatch('tutup', message: 'Data siswa berhasil dihapus.');
    }
};
?>

<div class="flex h-full flex-col">
    <div class="flex gap-2 border-b border-gray-200 py-2">
        @foreach (['siswa' => 'Aktif', 'lolos' => 'Lolos', 'cuti' => 'Cuti'] as $key => $label)
            <button wire:click="$set('status', '{{ $key }}')" @class([
                'px-2 py-1 text-sm font-medium transition-colors',
                'text-amber-900 border-b-2 border-amber-900' => $status === $key,
                'text-gray-500 hover:text-gray-700' => $status !== $key,
            ])>
                {{ $label }}
            </button>
        @endforeach
    </div>

    <div class="flex-1 space-y-1 overflow-y-auto pt-2">
        @forelse ($this->siswa as $s)
            <div wire:click.stop="pilihSiswa('{{ $s->nis }}')" wire:key="siswa-{{ $s->nis }}"
                @class([
                    'group flex cursor-pointer items-start gap-3 px-2 py-2 transition',
                    'bg-amber-50' => $selectedNis === $s->nis,
                    'hover:bg-gray-50' => $selectedNis !== $s->nis,
                ])>
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-semibold
                    {{ $s->list_w->isNotEmpty() ? 'bg-red-100 text-red-800' : 'bg-neutral-200 text-neutral-600' }}">
                    {{ $this->siswa->firstItem() + $loop->index }}
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-start gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-neutral-900">{{ $s->detail->nama_lengkap }}</p>
                            <p class="mt-0.5 text-xs text-neutral-500">
                                {{ optional(optional($s->detail)->tgl_lahir)->age ? optional(optional($s->detail)->tgl_lahir)->age . ' 歳' : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" class="relative shrink-0">
                    <button @click.stop="open = !open"
                        class="rounded px-2 py-1 text-neutral-400 transition hover:bg-neutral-200 hover:text-neutral-700">
                        ...
                    </button>

                    <div x-show="open" @click.outside="open = false" x-transition
                        class="absolute right-0 mt-2 z-10 w-40 rounded border border-neutral-200 bg-white p-1 shadow-md">
                        <ul class="text-sm">
                            <li>
                                <div wire:click.stop="$dispatchTo('detailsiswa', 'pilih-siswa', { nis: '{{ $s->nis }}' }); $dispatchTo('detailsiswa', 'edit-siswa')"
                                    class="cursor-pointer rounded px-2 py-2 text-neutral-700 hover:bg-neutral-100">
                                    Edit
                                </div>
                            </li>
                            <li>
                                <div
                                    x-on:click.stop="if (confirm('Hapus biodata siswa ini? Data tagihan dan transaksi tetap disimpan.')) { $wire.hapusSiswa('{{ $s->nis }}') }"
                                    class="cursor-pointer rounded px-2 py-2 text-red-700 hover:bg-red-50">
                                    Hapus
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex min-h-40 items-center justify-center bg-neutral-50 p-4 text-center text-sm text-neutral-500">
                Tidak ada siswa yang cocok dengan filter saat ini.
            </div>
        @endforelse
    </div>

    <div class="border-t border-neutral-200 py-2">
        {{ $this->siswa->links() }}
    </div>
</div>
