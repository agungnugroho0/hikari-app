<?php

namespace App\Livewire\Forms;

use App\Models\Kelas;
use App\Models\Staff;
use App\Services\KelasServices;
use Livewire\Attributes\Validate;
use Livewire\Form;

class KelasForm extends Form
{
    #[Validate('required', message: 'Silahkan isi Nama Kelas')]
    public $namakelas;

    #[Validate('required', message: 'Silahkan isi Tingkat Kelas')]
    public $tingkat;

    public $pengajar;

    public $staff;

    public $id_kelas;

    protected KelasServices $service;

    public function boot(KelasServices $services)
    {
        $this->service = $services;
        $this->staff = Staff::where('akses', '=', 'guru')->get();
    }

    public function store()
    {
        $this->validate();
        $this->service->create(
            $this->only(['namakelas', 'tingkat', 'pengajar'])
        );
    }

    public function setModels($id)
    {
        $kelas = Kelas::findorfail($id);
        $this->id_kelas = $kelas->id_kelas;
        $this->namakelas = $kelas->nama_kelas;
        $this->tingkat = $kelas->tingkat;
        $this->pengajar = $kelas->id_pengajar;
    }

    public function update()
    {
        $this->validate();
        $this->service->edit(
            $this->only('id_kelas', 'namakelas', 'tingkat', 'pengajar')
        );
    }
}
