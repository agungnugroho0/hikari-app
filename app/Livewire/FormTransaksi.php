<?php

namespace App\Livewire;

use App\Livewire\Forms\TransaksiForm as FormsTransaksiForm;
use App\Models\Core;
use Livewire\Component;

class FormTransaksi extends Component
{
    public Core $siswa;
    public FormsTransaksiForm $form;

    public function mount(Core $siswa)
    {
        $this->siswa = $siswa->loadMissing(['detail', 'listtagihan_siswa']);
        $this->form->setSiswa($this->siswa);
    }

    public function simpan()
    {
        $this->form->store();
        $this->dispatch('transaksi-tersimpan');
        $this->dispatch('tutup', message: 'Transaksi berhasil disimpan.');
    }

    public function batal()
    {
        $this->dispatch('transaksi-ditutup');
    }

    public function render()
    {
        return view('components.form.form-transaksi');
    }
}
