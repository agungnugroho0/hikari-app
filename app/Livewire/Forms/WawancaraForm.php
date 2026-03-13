<?php

namespace App\Livewire\Forms;

use App\Services\WawancaraServices;
use Livewire\Attributes\Validate;
use Livewire\Form;

class WawancaraForm extends Form
{
    protected WawancaraServices $service;

    public $nis;

    public $id_so;

    public $id_job;

    #[Validate('required', message: 'Tanggal tidak boleh kosong')]
    public $tgl_lolos;

    #[Validate('required|numeric', message: [
        'required' => 'Isi Jumlah Tagihan terlebih dahulu',
        'numeric' => 'Tagihan harus berupa angka',
    ])]
    public $tagihan;

    #[Validate('required|numeric', message: [
        'required' => 'Isi Jumlah Tagihan terlebih dahulu',
        'numeric' => 'Tagihan harus berupa angka',
    ])]
    public $tagihan_so;

    public function boot(WawancaraServices $service)
    {
        $this->service = $service;

    }

    public function store()
    {
        $this->validate();
        $this->service->create(
            $this->only([
                'nis', 'id_so', 'tgl_lolos', 'tagihan', 'tagihan_so', 'id_job',
            ])
        );
    }
}
