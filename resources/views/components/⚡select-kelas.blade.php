<?php
use Livewire\Component;
use App\Models\Kelas;

new class extends Component {
    public $kelas;

    public function mount()
    {
        $this->kelas = Kelas::all();
    }
};
?>

<div>
    <select wire:model.live="$parent.idkelas"
        class="block w-full rounded-2xl border border-neutral-200 bg-neutral-50 px-4 py-3 pr-10 text-sm text-neutral-800 outline-none transition focus:border-amber-900 focus:bg-white">
        <option value="">Semua Kelas</option>
        @foreach ($kelas as $k)
            <option value="{{ $k->id_kelas }}">Kelas {{ $k->nama_kelas }}</option>
        @endforeach
    </select>
</div>
