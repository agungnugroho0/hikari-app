<?php
use App\Livewire\Forms\StaffForm;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public StaffForm $staff;

    public function create(){
        $this->staff->store();
        $this->dispatch('tutup',message: 'Staff berhasil ditambahkan!')->to(Blade::component('Staff', Staff::class));
    }
};
?>

<div>
    <form wire:submit.prevent="create" class="grid grid-cols-2 gap-3">
        <div class="flex flex-col">
            <label for="nama_s" class="text-xs text-gray-600 py-1 mt-2">Nama Lengkap</label>
            <input wire:model.defer="staff.nama_s" type="text" name="nama_s" id="nama_s"
                class="border-gray-500 focus:ring-red-800 rounded active:ring-red-200">
            @error('staff.nama_s')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col">
            <label for="username" class="text-xs text-gray-600 py-1 mt-2">Username</label>
            <input wire:model.defer="staff.username" type="text" name="username" id="username"
                class="border-gray-500 focus:ring-red-800 rounded active:ring-red-200">
            @error('staff.username')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col">
            <label for="no" class="text-xs text-gray-600 py-1 mt-2">No</label>
            <input wire:model.defer="staff.no" type="text" name="no" id="no"
                class="border-gray-500 focus:ring-red-800 rounded active:ring-red-200">
            @error('staff.no')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col">
            <label for="akses" class="text-xs text-gray-600 py-1 mt-2">Akses</label>
            <select wire:model.defer="staff.akses" name="akses" id="akses"
                class="border-gray-500 focus:ring-red-800 rounded active:ring-red-200">
                <option value="">Pilih Akses</option>
                <option value="guru">guru</option>
                <option value="admin">admin</option>
            </select>
            @error('staff.akses')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex-1">
            <label class="block mb-2.5 text-sm font-medium text-heading" for="file_input" >Upload file</label>
            <input wire:model="staff.foto_s"
                class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full shadow-xs placeholder:text-body"
                id="file_input" type="file" accept="image/png,image/jpeg,image/jpg">
            <div wire:loading wire:target="staff.foto_s">Uploading...</div>
            @error('staff.foto_s')
                <span class="error text-orange-600">{{ $message }}</span>
            @enderror
        </div>
    <div>
        @if ($this->staff->foto_s)
                    <img src="{{ $this->staff->foto_s->temporaryUrl() }}" class="rounded w-10 h-10">
                @endif
    </div>
        <div>
            <button type="submit" wire:loading.attr="disabled" wire:target="create"
                class="bg-red-900 p-2 cursor-pointer text-white hover:bg-red-800 transition-all rounded shadow font-bold disabled:opacity-60 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="create">Simpan</span>
                <span wire:loading wire:target="create">Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
