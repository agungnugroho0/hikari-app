<?php

namespace App\Livewire\Forms;

use App\Models\So;
use App\Services\SoService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SoForm extends Form
{
    protected SoService $service;

    public $idso;

    #[Validate('required', message: 'Isi Nama Sending Organizer')]
    public $nama_so;

    #[Validate('required', message: 'Isi Lokasi Sending Organizer')]
    public $lokasi;

    #[Validate('required', message: 'Isi Penanggung Jawab Sending Organizer')]
    public $pj;

    #[Validate('required', message: 'Isi Keterangan Sending Organizer')]
    public $ket;

    #[Validate('nullable|image|mimes:jpg,jpeg,png|max:3072', message: [
        'mimes' => 'file harus JPEG,JPG,PNG',
        'max' => 'File ukuran Maksimal 3MB',
        'image' => 'Files harus berupa gambar',
    ])]
    public $foto_so;

    public function boot(SoService $services)
    {
        $this->service = $services;
    }

    public function store()
    {
        $this->validate();
        $this->service->create(
            $this->only(['nama_so', 'pj', 'foto_so', 'lokasi', 'ket'])
        );
    }

    public function setModels($id)
    {
        $so = So::findorfail($id);
        $this->idso = $so->id_so;
        $this->nama_so = $so->nama_so;
        $this->foto_so = $so->foto_so;
        $this->lokasi = $so->lokasi;
        $this->pj = $so->pj;
        $this->ket = $so->ket;
    }

    public function update()
    {
        $this->validate();
        $this->service->edit(
            $this->only(['idso', 'nama_so', 'pj', 'foto_so', 'lokasi', 'ket'])
        );
    }
}
