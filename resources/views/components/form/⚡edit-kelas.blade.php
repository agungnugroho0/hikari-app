<?php

use Livewire\Component;
use App\Livewire\Forms\KelasForm;


new class extends Component
{
    public KelasForm $kls;

    public function mount($id){
        $this->kls->setModels($id);
    }

    public function edit(){
        $this->kls->update();
        $this->dispatch('tutup', message: 'Kelas Berhasil diedit')->to(Blade::component('Kelas', Kelas::class));;
    }
};
?>

<div>
   <form wire:submit.prevent="edit" class="grid grid-cols-2 gap-3">
    <input type="text" wire:model="kls.id_kelas" hidden>
        <div class="flex flex-col">
            <label for="namakelas" class="text-xs text-gray-600 py-1 mt-2">Nama Kelas</label>
            <input wire:model.defer="kls.namakelas" type="text" name="namakelas" id="namakelas"
                class="border-gray-500 focus:ring-red-800 rounded active:ring-red-200">
            @error('kls.namakelas')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col">
            <label for="tingkat" class="text-xs text-gray-600 py-1 mt-2">Tingkat</label>
            <input wire:model.defer="kls.tingkat" type="number" name="tingkat" id="tingkat"
                class="border-gray-500 focus:ring-red-800 rounded active:ring-red-200">
            @error('kls.tingkat')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col">
            <label for="pengajar" class="text-xs text-gray-600 py-1 mt-2">Wali Kelas</label>
            
            <select wire:model.defer="kls.pengajar" name="pengajar" id="pengajar"
                class="border-gray-500 focus:ring-red-800 rounded active:ring-red-200">
                <option value="">Pilih Wali Kelas</option>
                @foreach ( $kls->staff as $s)
                    <option value="{{$s['id_staff']}}">{{$s['nama_s']}}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col">
            <p wire:loading.remove wire:target="edit" class="text-xs text-gray-600 py-1 mt-2">Simpan</p>
            <p wire:loading wire:target="edit" class="text-xs text-gray-600 py-1 mt-2">Menyimpan...</p>
            <button type="submit" wire:loading.attr="disabled" wire:target="edit"
                class="bg-red-900 p-2 cursor-pointer text-white hover:bg-red-800 transition-all rounded shadow font-bold disabled:opacity-60 disabled:cursor-not-allowed">
                <span>Edit Kelas</span>
            </button>
        </div>
    </form>
</div>