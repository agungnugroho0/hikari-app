<?php

use Livewire\Component;
use App\Livewire\Forms\JobfairForm;
use App\Models\So;
new class extends Component {
    public JobfairForm $jobfair;

    public $so;
    public function mount()
    {
        $this->so = So::all();
    }

    public function insert(){
        $this->jobfair->store();
        $this->dispatch('jobfair-updated');
        $this->dispatch('tutupforms', message: 'Data berhasil disimpan!');
    }
};
?>

<div>
    <form wire:submit.prevent="insert" class="grid grid-cols-2 gap-3">
        <div class="flex flex-col">
            <label for="nama_job" class="text-xs text-gray-600 py-1 mt-2">Nama Pekerjaan</label>
            <input wire:model="jobfair.nama_job" type="text" name="nama_job" id="nama_job"
                class="border-gray-500 focus:ring-red-800 rounded">
            @error('jobfair.nama_job')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex flex-col">
            <label for="jobfair.perusahaan" class="text-xs text-gray-600 py-1 mt-2">Nama Perusahaan</label>
            <input wire:model="jobfair.perusahaan" type="text" name="perusahaan" id="perusahaan"
                class="border-gray-500 focus:ring-red-800 rounded">
            @error('jobfair.perusahaan')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex flex-col">
            <label for="id_so" class="text-xs text-gray-600 py-1 mt-2">Nama Sending Organizer</label>
            <select wire:model="jobfair.id_so" name="id_so" id="id_so"
                class="border-gray-500 focus:ring-red-800 rounded">
                <option value="">Pilih SO</option>
                @foreach ($this->so as $s)
                    <option value="{{ $s->id_so }}">{{ $s->nama_so }}</option>
                @endforeach
            </select>
            @error('jobfair.id_so')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex flex-col">
            <label for="tgl_wawancara" class="text-xs text-gray-600 py-1 mt-2">Tanggal Wawancara</label>
            <input wire:model="jobfair.tgl_wawancara" type="date" name="tgl_wawancara" id="tgl_wawancara"
                class="border-gray-500 focus:ring-red-800 rounded">
        </div>
        <div class="flex flex-col">
            <label wire:model="jobfair.penempatan" for="penempatan"
                class="text-xs text-gray-600 py-1 mt-2">Penempatan</label>
            <input type="text" wire:model="jobfair.penempatan" name="penempatan" id="penempatan" class="border-gray-500 focus:ring-red-800 rounded">
        </div>
        <div class="flex flex-col">
            <label for="metode" class="text-xs text-gray-600 py-1 mt-2">Model Seleksi</label>
            <select wire:model="jobfair.metode" name="metode" id="metode"
                class="border-gray-500 focus:ring-red-800 rounded">
                <option value="">Pilih Metode Seleksi</option>
                <option value="online">Online</option>
                <option value="offline">Offline</option>
                <option value="sending data">Sending Data</option>
            </select>
            @error('jobfair.metode')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>
        <button wire:loading.attr="disabled"
            class="bg-red-900 p-3 font-bold text-white hover:bg-red-700 transition cursor-pointer">Tambah Job
            Order</button>
    </form>
</div>
