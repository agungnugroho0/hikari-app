<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\JobfairForm;
use App\Livewire\Forms\WawancaraForm;
use App\Models\Core;

new class extends Component
{
    public JobfairForm $jobfair;
    public WawancaraForm $wawancara;
    public $nama_so;
    public $nis;
    public $nama_lengkap;
    
    #[On('formlolos')]
    public function formlolos($data){
        $this->jobfair->setModels($data['id_job']);
        $this->nama_so = $this->jobfair->job->list_so->nama_so;
        $this->nis = $data['nis'];
        $core = Core::with('detail')
        ->where('nis', $this->nis)
        ->first();

        $this->nama_lengkap = $core?->detail?->nama_lengkap;
    }

    public function submit(){
        $this->wawancara->id_job = $this->jobfair->id_job;
        $this->wawancara->id_so = $this->jobfair->id_so;
        $this->wawancara->nis = $this->nis;
        $this->wawancara->store();
        $this->dispatch('jobfair-updated');
        $this->dispatch('tutupforms', message: 'Peserta dinyatakan lolos', celebrate: true);
    }
    
};
?>

<div x-data="{
    formatRibuan(value) {
        const angka = String(value ?? '').replace(/\D/g, '');
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
}" class="mt-2">
    <form wire:submit.prevent="submit" class="grid grid-cols-2 gap-3">
        <input type="text" name="id_so" id="id_so" wire:model="jobfair.id_so" hidden>
        <div class="flex flex-col">
            <label for="nama_job" class="text-xs text-gray-600 py-1 mt-2">Nama Pekerjaan</label>
            <input wire:model="jobfair.nama_job" type="text" name="nama_job" id="nama_job"
                class="border-none bg-gray-100 rounded cursor-default" readonly>
        </div>
        <div class="flex flex-col">
            <label for="perusahaan" class="text-xs text-gray-600 py-1 mt-2">Nama Perusahaan</label>
            <input wire:model="jobfair.perusahaan" type="text" name="nama_job" id="nama_job"
                class="border-none bg-gray-100 rounded cursor-default" readonly>
        </div>
        <div class="flex flex-col">
            <label for="so" class="text-xs text-gray-600 py-1 mt-2">Nama Sending Organizer</label>
            <input type="text" name="so" id="so" value="{{ $nama_so }}"
                class="border-none bg-gray-100 rounded cursor-default" readonly>
        </div>
        <div class="flex flex-col">
            <label for="nama_siswa" class="text-xs text-gray-600 py-1 mt-2">NIP : {{ $nis }}</label>
            <input type="text" name="nama_siswa" id="nama_siswa" value="{{ $nama_lengkap}}"
                class="border-none bg-gray-100 rounded cursor-default" readonly>
        </div>
        <div class="flex flex-col">
            <label for="tgl_lolos" class="text-xs text-gray-600 py-1 mt-2">Tanggal Lolos</label>
            <input type="date" wire:model="wawancara.tgl_lolos" 
            class="border-red-200 focus:ring-0 rounded cursor-pointer" >
            @error('wawancara.tgl_lolos')
                <span class="error text-orange-600">{{ $message }}</span>
                @enderror
        </div>
        <div class="flex flex-col">
            <label for="tagihan_hikari" class="text-xs text-gray-600 py-1 mt-2">Tagihan Hikari</label>
            <input type="text" name="tagihan_hikari" id="tagihan_hikari" inputmode="numeric"
                x-data="{ display: formatRibuan($wire.wawancara.tagihan) }"
                x-init="$watch(() => $wire.wawancara.tagihan, value => display = formatRibuan(value))"
                x-model="display"
                x-on:input="
                    const raw = $event.target.value.replace(/\D/g, '');
                    display = formatRibuan(raw);
                    $wire.set('wawancara.tagihan', raw);
                "
                class="border-red-200 focus:ring-0 rounded" >
                @error('wawancara.tagihan')
                <span class="error text-orange-600">{{ $message }}</span>
                @enderror
        </div>
        <div class="flex flex-col">
            <label for="tagihan_so" class="text-xs text-gray-600 py-1 mt-2">Tagihan SO {{ $nama_so }}</label>
            <input type="text" name="tagihan_so" id="tagihan_so" inputmode="numeric"
                x-data="{ display: formatRibuan($wire.wawancara.tagihan_so) }"
                x-init="$watch(() => $wire.wawancara.tagihan_so, value => display = formatRibuan(value))"
                x-model="display"
                x-on:input="
                    const raw = $event.target.value.replace(/\D/g, '');
                    display = formatRibuan(raw);
                    $wire.set('wawancara.tagihan_so', raw);
                "
                class="border-red-200 focus:ring-0 rounded" >
                @error('wawancara.tagihan_so')
                <span class="error text-orange-600">{{ $message }}</span>
                @enderror
        </div>
        <div class="flex flex-col">
            <label for="tagihan_so" class="text-xs text-gray-600 py-1 mt-2">&nbsp;</label>
         <button wire:submit wire:loading.attr="disabled" wire:target="submit"
            class="bg-red-900 p-3 font-bold text-white hover:bg-red-700 transition cursor-pointer rounded">Lolos</button>
        </div>
    </form>
    {{-- loading time --}}
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:loading wire:target="submit">
        <div role="status">
            <svg aria-hidden="true"
                class="w-8 h-8 text-neutral-tertiary animate-spin fill-red-900 mx-auto translate-y-5"
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
