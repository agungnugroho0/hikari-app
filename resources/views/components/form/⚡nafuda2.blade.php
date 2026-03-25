<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Forms\SettingsForm;

new class extends Component
{
    use WithFileUploads;
    public SettingsForm $nafuda2;

    public function mount(){
        $this->nafuda2->setnafuda2();
    }

    public function update(){
        $this->nafuda2->editnafuda();
        $this->dispatch('kirim', message: 'nafuda berhasil diedit!')->to(Blade::component('Setelan', Setelan::class));
        $this->nafuda2->setnafuda2();
    }
};
?>

<div>
        <form wire:submit.prevent="update" class="grid grid-cols-2 gap-3"  enctype="multipart/form-data">
            <input type="text" wire:model="nafuda2.id_st" hidden/>
            <input type="text" wire:model="nafuda2.nama_set" hidden/>
            <div class="mt-2 gap-2">
                <label class="block mb-2.5 text-sm font-medium text-heading" for="file_input" >Ganti nafuda 2</label>
                <div class="flex gap-2">
                    <img src="{{  $nafuda2->ket instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
    ? $nafuda2->ket->temporaryUrl()
    : ($nafuda2->ket_path 
        ? asset('storage/'.$nafuda2->ket_path) 
        : asset('storage/foto/foto.jpeg')
    ) }}"  class="rounded w-12 h-12 object-cover shadow">  
                    <input wire:model="nafuda2.ket"
                    class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full shadow-xs placeholder:text-body"
                    id="file_input_2" type="file" accept="image/png,image/jpeg,image/jpg">
                    <button type="submit" wire:loading.attr="disabled" wire:target="update"
                    class="bg-red-900 p-2 cursor-pointer text-white hover:bg-red-800 transition-all rounded shadow font-bold disabled:opacity-60 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="update">Set</span>
                    </button>
                </div>
                
                <div wire:loading wire:target="nafuda2.ket">Uploading...</div>
            </div>
        </form>    
       
<x-loading wire:loading wire:target="update"></x-loading>
    
</div>