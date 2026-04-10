<?php

use App\Livewire\Forms\SettingsForm;
use Livewire\Component;

new class extends Component
{
    public SettingsForm $kelasSaatIni;

    public function mount(): void
    {
        $this->kelasSaatIni->setKelasSaatIni();
    }

    public function update(): void
    {
        $this->kelasSaatIni->editKelasSaatIni();
        $this->dispatch('kirim', message: 'Kelas saat ini berhasil diupdate!');
        $this->kelasSaatIni->setKelasSaatIni();
    }
};
?>

<div>
    <form wire:submit.prevent="update" class="grid grid-cols-2 gap-3">
        <input type="text" wire:model="kelasSaatIni.id_st" hidden>
        <input type="text" wire:model="kelasSaatIni.nama_set" hidden>
        <div class="flex flex-col">
            <label for="kelas_saat_ini" class="text-xs text-gray-600 py-1 mt-2">Kelas Baru</label>
            <div class="flex gap-2">
                <select id="kelas_saat_ini" wire:model="kelasSaatIni.ket"
                    class="border-gray-500 focus:ring-red-800 rounded active:ring-red-200 w-full">
                    <option value="">Pilih kelas baru</option>
                    @foreach ($kelasSaatIni->kelasOptions as $kelas)
                        <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
                <button type="submit" wire:loading.attr="disabled" wire:target="update"
                    class="bg-red-900 p-2 cursor-pointer text-white hover:bg-red-800 transition-all rounded shadow font-bold disabled:opacity-60 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="update">Set</span>
                </button>
            </div>
            @error('kelasSaatIni.ket') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </form>
    <x-loading wire:loading wire:target="update"></x-loading>
</div>
