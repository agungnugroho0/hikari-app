<?php

namespace App\Livewire\Forms;

use App\Models\Core;
use App\Models\Transaksi;
use App\Services\TransaksiServices;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransaksiForm extends Form
{
    protected TransaksiServices $services;

    public ?Core $siswa = null;
    public ?Transaksi $transaksi = null;

    public $id_tx = '';

    #[Validate('required|exists:tagihan,id_t', message: [
        'required' => 'Pilih tagihan terlebih dahulu.',
        'exists' => 'Tagihan tidak valid.',
    ])]
    public $id_t = '';

    #[Validate('required|date', message: [
        'required' => 'Tanggal transaksi wajib diisi.',
        'date' => 'Tanggal transaksi tidak valid.',
    ])]
    public $tgl_transaksi = '';

    #[Validate('required|integer|min:1', message: [
        'required' => 'Nominal wajib diisi.',
        'integer' => 'Nominal harus berupa angka.',
        'min' => 'Nominal minimal 1.',
    ])]
    public $nominal = '';

    public function boot(TransaksiServices $services)
    {
        $this->services = $services;
    }

    public function setSiswa(Core $siswa)
    {
        $this->siswa = $siswa->loadMissing(['detail', 'listtagihan_siswa']);
        $this->tgl_transaksi = now()->format('Y-m-d');
    }

    public function setModel(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;
        $this->id_tx = $transaksi->id_tx;
        $this->id_t = $transaksi->id_t;
        $this->tgl_transaksi = $transaksi->tgl_transaksi;
        $this->nominal = $transaksi->nominal;
    }

    public function store()
    {
        $this->validate();

        $this->services->create(
            $this->only([
                'id_t', 'tgl_transaksi', 'nominal',
            ]),
            $this->siswa
        );
    }

    public function update()
    {
        $this->validate();

        $this->services->edit(
            $this->only([
                'id_tx', 'id_t', 'tgl_transaksi', 'nominal',
            ]),
            $this->siswa
        );
    }
}
