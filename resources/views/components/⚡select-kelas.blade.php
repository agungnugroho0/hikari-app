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
        class="block pl-3 pr-8 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm focus:ring-offset-fg-warning focus:border-offset-fg-warning shadow-xs placeholder:text-body">
        <option value="">Semua Kelas</option>
        @foreach ($kelas as $k)
            <option value="{{ $k->id_kelas }}">Kelas {{ $k->nama_kelas }}</option>
        @endforeach
    </select>


</div>
