<?php
use App\Livewire\Forms\KelasForm;
use Livewire\Component;

new class extends Component
{
    public KelasForm $kls;

    public function create(){
        $this->kls->store();
        $this->dispatch('tutup', message: 'Kelas Berhasil ditambahkan')->to(Blade::component('Kelas', Kelas::class));;
    }
};
?>

<div>
    <div class="bg-yellow-100 px-1 m-1"><span class="text-xs text-orange-600">Tingkat tertinggi adalah 0</span></div>
    <form wire:submit.prevent="create" class="grid grid-cols-2 gap-3">
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
                <option value="">Pilih Kelas</option>
                @foreach ( $kls->staff as $s)
                    <option value="{{$s['id_staff']}}">{{$s['nama_s']}}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col">
            <p wire:loading.remove wire:target="create" class="text-xs text-gray-600 py-1 mt-2">Simpan</p>
            <p wire:loading wire:target="create" class="text-xs text-gray-600 py-1 mt-2">Menyimpan...</p>
            <button type="submit" wire:loading.attr="disabled" wire:target="create"
                class="bg-red-900 p-2 cursor-pointer text-white hover:bg-red-800 transition-all rounded shadow font-bold disabled:opacity-60 disabled:cursor-not-allowed">
                <span>+ Tambah Kelas</span>
            </button>
        </div>
    </form>

</div>