<?php
use App\Livewire\Forms\SiswaForm;
use Livewire\Component;
use App\Models\Core;
use App\Models\Kelas;
use Livewire\WithFileUploads;
new class extends Component {
    use WithFileUploads;
    public SiswaForm $form;

    public $kls;
    public function mount(Core $siswa)
    {
        $this->kls = Kelas::all();
        $this->form->setModel($siswa);
    }

    public function simpan()
    {
        $this->form->update();
        $this->dispatch('siswa-updated');
        $this->dispatch('pilih-siswa', $this->form->nis);
    }
};
?>

<div>
    <form wire:submit.prevent="simpan">

        <div class="flex gap-2">
            <div>
                <label for="" class="text-normal font-medium text-gray-400 pl-2">Nis</label><br>
                <input readonly type="text" wire:model="form.nis" class="border-0 bg-slate-100 rounded mt-1 my-2">
            </div>
            <div class="flex-1">
                <label class="block mb-2.5 text-sm font-medium text-heading" for="file_input">Upload file</label>
                <input wire:model="form.foto"
                    class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full shadow-xs placeholder:text-body"
                    id="file_input" type="file">
                <div wire:loading wire:target="form.foto">Uploading...</div>
                @if ($this->form->foto)
                    <img src="{{ $this->form->foto->temporaryUrl() }}" class="rounded w-10 h-10">
                @endif
            </div>
        </div>
        <br>
        <label for="nama_lengkap" class="text-normal font-medium text-gray-600 pl-2">Nama Lengkap</label><br>
        <input id="nama_lengkap" type="text" wire:model="form.nama_lengkap"
            class="w-full border-slate-400 rounded mt-1 my-2">
        <div class="grid gap-2 md:grid-cols-3">
            <div>
                <label for="panggilan" class="text-normal font-medium text-gray-600 pl-2">Panggilan
                    (Katakana)</label><br>
                <input id="panggilan" type="text" wire:model="form.panggilan"
                    class="w-full border-slate-400 rounded mt-1 my-2">
            </div>
            <div>
                <label for="kelas" class="text-normal font-medium text-gray-600 pl-2">Kelas</label><br>
                <select name="kelas" id="kelas" wire:model="form.id_kelas"
                    class="w-full border-slate-400 rounded mt-1 my-2">
                    @foreach ($kls as $kelas)
                        <option value="{{ $kelas->id_kelas }}">
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="text-normal font-medium text-gray-600 pl-2">Keaktifan Siswa</label><br>
                <select name="status" id="status" wire:model="form.status"
                    class="w-full border-slate-400 rounded mt-1 my-2">
                    <option value="siswa">Aktif</option>
                    <option value="lolos">Lolos</option>
                    <option value="cuti">Cuti</option>
                </select>
            </div>
        </div>
        <div class="grid gap-2 md:grid-cols-3">
            <div>
                <label for="tgl_lahir" class="text-normal font-medium text-gray-600 pl-2">Tanggal Lahir</label><br>
                <input id="tgl_lahir" type="date" wire:model="form.tgl_lahir"
                    class="w-full border-slate-400 rounded mt-1 my-2">
            </div>
            <div>
                <label for="tempat_lhr" class="text-normal font-medium text-gray-600 pl-2">Tempat Lahir</label><br>
                <input id="tempat_lhr" type="text" wire:model="form.tempat_lhr"
                    class="w-full border-slate-400 rounded mt-1 my-2">
            </div>
            <div>
                <label for="gender" class="text-normal font-medium text-gray-600 pl-2">Jenis Kelamin</label><br>
                <select name="gender" id="gender" wire:model="form.gender"
                    class="w-full border-slate-400 rounded mt-1 my-2">
                    <option value="L">Laki - laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
        </div>
        <br>
        <label for="alamat" class="text-normal font-medium text-gray-600 pl-2">Alamat</label><br>
        <input id="alamat" type="text" wire:model="form.alamat" class="w-full border-slate-400 rounded mt-1 my-2">
        <div class="grid gap-2 md:grid-cols-3">
            <div>
                <label for="wa" class="text-normal font-medium text-gray-600 pl-2">No Whatsapp</label><br>
                <input id="wa" type="text" wire:model="form.wa"
                    class="w-full border-slate-400 rounded mt-1 my-2">
            </div>
            <div>
                <label for="wa_wali" class="text-normal font-medium text-gray-600 pl-2">No Whatsapp Wali</label><br>
                <input id="wa_wali" type="text" wire:model="form.wa_wali"
                    class="w-full border-slate-400 rounded mt-1 my-2">
            </div>
            <div>
                <label for="pernikahan" class="text-normal font-medium text-gray-600 pl-2">Status</label><br>
                <select name="pernikahan" id="pernikahan" wire:model="form.pernikahan"
                    class="w-full border-slate-400 rounded mt-1 my-2">
                    <option value="single">Single</option>
                    <option value="menikah">Menikah</option>
                    <option value="cerai">Cerai</option>
                </select>
            </div>
        </div>
        <button wire:submit
            class="bg-red-900 p-2 cursor-pointer text-white hover:bg-red-800 transition-all rounded shadow font-bold">Simpan
        </button>

    </form>
</div>
