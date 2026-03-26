<?php

use Livewire\Component;
use App\Livewire\Forms\SettingsForm;
new class extends Component
{
    // public $ukk;
    public SettingsForm $ukk;
    public function update(){
        $this->ukk->editukk();
        $this->dispatch('kirim', message: 'Kenaikan Kelas berhasil diupdate!')->to(Blade::component('Setelan', Setelan::class));
    }
    public function mount()
    {
        $this->ukk->setukk();
    }
};
?>

<div>
    <form wire:submit.prevent="update" class="grid grid-cols-2 gap-3">
        <input type="text" wire:model="ukk.id_st" hidden>
        <input type="text" wire:model="ukk.nama_set" hidden>
        <div class="flex flex-col">
            <label for="nama_set" class="text-xs text-gray-600 py-1 mt-2">Tanggal Kenaikan Kelas</label>
            <div class="flex gap-2">
            <input wire:model="ukk.ket" type="date" name="ket" id="ukk"
                class="border-gray-500 focus:ring-red-800 rounded active:ring-red-200 w-full">
            <button type="submit" wire:loading.attr="disabled" wire:target="update"
                class="bg-red-900 p-2 cursor-pointer text-white hover:bg-red-800 transition-all rounded shadow font-bold disabled:opacity-60 disabled:cursor-not-allowed">
                {{-- <span wire:loading wire:target="update">Merubah data...</span> --}}
                <span wire:loading.remove wire:target="update">Set</span>
                
            </button>
            </div>
    </div>
</form>
<x-loading wire:loading wire:target="update"></x-loading>
</div>