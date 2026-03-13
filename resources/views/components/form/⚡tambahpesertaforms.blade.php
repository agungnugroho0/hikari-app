<?php

use Livewire\Component;
use App\Livewire\Forms\JobfairForm;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use App\Models\Core;
use Illuminate\Database\Eloquent\Builder;

new class extends Component {
    public JobfairForm $jobfair;

    public $search;

    #[On('tambahpeserta')]
    public function set($id)
    {
        $this->jobfair->setModels($id);
    }

    #[Computed]
    public function daftarSiswa()
    {
        $query = Core::with('list_w', 'detail')->where('status', 'siswa');

        if (!empty($this->search)) {
            $query->whereHas('detail', function (Builder $q) {
                $q->where('nama_lengkap', 'like', "%{$this->search}%");
            });
        }

        return $query->get();
    }

    public function store()
    {
        $this->jobfair->storePeserta();
        $this->dispatch('tutupforms', message: 'Peserta Berhasil ditambahkan');
    }
};
?>

<div>
    <div class="grid grid-cols-2 gap-3">
        <div class="flex flex-col">
            <label for="nama_job" class="text-xs text-gray-600 py-1 mt-2">Nama Pekerjaan</label>
            <input wire:model="jobfair.nama_job" type="text" name="nama_job" id="nama_job"
                class="bg-gray-200 border-0 focus:ring-red-800 rounded" readonly />
        </div>
        <div class="flex flex-col">
            <label for="jobfair.perusahaan" class="text-xs text-gray-600 py-1 mt-2">Nama Perusahaan</label>
            <input wire:model="jobfair.perusahaan" type="text" name="perusahaan" id="perusahaan"
                class="bg-gray-200 border-0 focus:ring-red-800 rounded" readonly />
        </div>
        <div class="flex flex-col">
            <label for="tgl_wawancara" class="text-xs text-gray-600 py-1 mt-2">Tanggal Wawancara</label>
            <input wire:model="jobfair.tgl_wawancara" type="date" name="tgl_wawancara" id="tgl_wawancara"
                class="bg-gray-200 border-0 focus:ring-red-800 rounded" readonly>
        </div>
        <div class="flex flex-col">
            <label wire:model="jobfair.penempatan" for="penempatan"
                class="text-xs text-gray-600 py-1 mt-2">Penempatan</label>
            <input type="text" name="penempatan" id="penempatan"
                class="bg-gray-200 border-0 focus:ring-red-800 rounded">
        </div>
        <div class="flex flex-col">
            <label for="metode" class="text-xs text-gray-600 py-1 mt-2">Model Seleksi</label>
            <input type="text" wire:model="jobfair.metode" name="metode" id="metode"
                class="bg-gray-200 border-0 focus:ring-red-800 rounded" readonly />

        </div>
    </div>
    <hr class="my-6">
    <input type="search" wire:model.live.debounce.250ms="search"
        class="w-full px-2 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded"
        placeholder="Search" />
    <form wire:submit.prevent="store">
        <div class="grid grid-cols-2 gap-2 mt-2">
            @foreach ($this->daftarSiswa as $s)
                <div>
                    <input type="checkbox" wire:model.live="jobfair.siswa" id="{{ $s->nis }}"
                        value="{{ $s->nis }}" @disabled($s->list_w()->exists())
                        class="w-4 h-4 
           {{ $s->list_w()->exists() ? 'cursor-not-allowed opacity-40 accent-gray-400' : 'accent-blue-600' }}">
                    <label for="{{ $s->nis }}"
                        class="p-2 {{ $s->list_w()->exists() ? 'cursor-not-allowed opacity-40 accent-gray-200' : 'accent-blue-600' }}">{{ $s->detail->nama_lengkap }}</label>
                </div>
            @endforeach
        </div>
        {{-- button --}}
        <div class="flex gap-2 items-center">
            <button wire:submit wire:loading.attr="disabled"
                class="bg-red-900 mt-2 p-2 font-bold text-white hover:bg-red-700 transition cursor-pointer rounded">Tambah
                Peserta
            </button>
            <div role="status" wire:loading>
                <svg aria-hidden="true" class="w-8 h-8 text-neutral-tertiary animate-spin fill-brand"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </form>
    {{-- @dd($this->daftarSiswa) --}}

</div>
