<?php
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Forms\SoForm;

new class extends Component
{
    use WithFileUploads;

    public SoForm $s;

    public function create(){
        $this->s->store();
        $this->dispatch('tutup', message: 'SO Berhasil ditambahkan')->to(Blade::component('SendingOrganizer', SendingOrganizer::class));;
    }
};
?>

<div>
    <form wire:submit.prevent="create" class="grid grid-cols-2 gap-3">
        <div class="flex flex-col">
            <label for="nama_so" class="text-xs text-gray-600 py-1 mt-2">Nama SO</label>
            <input wire:model.defer="s.nama_so" type="text" name="nama_so" id="nama_so"
                class="border-gray-500 focus:ring-red-800 rounded active:ring-red-200">
            @error('s.nama_so')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col">
            <label for="pj" class="text-xs text-gray-600 py-1 mt-2">Penanggung Jawab</label>
            <input wire:model.defer="s.pj" type="text" name="pj" id="pj"
                class="border-gray-500 focus:ring-red-800 rounded active:ring-red-200">
            @error('s.pj')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex flex-col">
            <label for="lokasi" class="text-xs text-gray-600 py-1 mt-2">Lokasi</label>
            <input wire:model.defer="s.lokasi" type="text" name="lokasi" id="lokasi"
                class="border-gray-500 focus:ring-red-800 rounded active:ring-red-200">
            @error('s.lokasi')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex-1">
            <label class="block mb-2.5 text-sm font-medium text-heading" for="file_input" >Upload Logo SO</label>
            <input wire:model="s.foto_so"
                class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full shadow-xs placeholder:text-body"
                id="file_input" type="file" accept="image/png,image/jpeg,image/jpg">
            <div wire:loading wire:target="s.foto_so">Uploading...</div>
            @error('s.foto_so')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col">
            <p wire:loading.remove wire:target="create" class="text-xs text-gray-600 py-1 mt-2">Simpan</p>
            <p wire:loading wire:target="create" class="text-xs text-gray-600 py-1 mt-2">Menyimpan...</p>
            <button type="submit" wire:loading.attr="disabled" wire:target="create"
                class="bg-red-900 p-2 cursor-pointer text-white hover:bg-red-800 transition-all rounded shadow font-bold disabled:opacity-60 disabled:cursor-not-allowed">
                <span>+ Tambah SO</span>
            </button>
        </div>
    
</div>