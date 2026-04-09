<?php

namespace App\Livewire\Pages;

use App\Livewire\Forms\SiswaForm;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Pendaftaran Siswa Baru')]
class PendaftaranSiswaBaru extends Component
{
    use WithFileUploads;

    public SiswaForm $form;

    public bool $submitted = false;

    public function mount(): void
    {
        $this->form->setCurrentClassFromSettings();
        $this->form->pernikahan = 'single';
    }

    public function simpan(): void
    {
        if (blank($this->form->id_kelas)) {
            $this->addError('form.id_kelas', 'Kelas aktif belum diatur pada settings.');

            return;
        }

        $this->form->storePublic();
        $this->submitted = true;
    }

    public function daftarLagi(): void
    {
        $this->reset('submitted');
        $this->resetErrorBag();
        $this->form->reset([
            'foto',
            'nama_lengkap',
            'panggilan',
            'tgl_lahir',
            'gender',
            'tempat_lhr',
            'alamat',
            'alamat_desa',
            'alamat_rt',
            'alamat_rw',
            'alamat_kecamatan',
            'alamat_kabupaten',
            'alamat_provinsi',
            'wa',
            'wa_wali',
            'agama',
        ]);
        $this->form->setCurrentClassFromSettings();
        $this->form->pernikahan = 'single';
        $this->form->submittedNis = null;
    }

    public function render()
    {
        return view('pages.pendaftaran-siswa-baru');
    }
}
