<?php

namespace App\Livewire;

use App\Livewire\Forms\TagihanForm as FormsTagihanForm;
use App\Models\Core;
use Livewire\Component;

class FormTagihan extends Component
{
    public Core $siswa;
    public FormsTagihanForm $form;

    public function mount(Core $siswa)
    {
        $this->siswa = $siswa->loadMissing(['detail', 'listlolos', 'listtagihan_siswa']);
        $this->form->setSiswa($this->siswa);
    }

    public function simpan()
    {
        $this->form->store();
        $this->dispatch('tagihan-tersimpan');
        $this->dispatch('tutup', message: 'Tagihan berhasil disimpan.');
    }

    public function batal()
    {
        $this->dispatch('tagihan-ditutup');
    }

    public function render()
    {
        return view('components.form.form-tagihan');
    }
}
