<?php

namespace App\Livewire\Forms;

use App\Models\Core;
use App\Models\Tagihan;
use App\Services\TagihanServices;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TagihanForm extends Form
{
    protected TagihanServices $services;

    public ?Core $siswa = null;
    public ?Tagihan $tagihan = null;

    public $id_t = '';

    #[Validate('required|date', message: [
        'required' => 'Tanggal terbit wajib diisi.',
        'date' => 'Tanggal terbit tidak valid.',
    ])]
    public $tgl_terbit = '';

    #[Validate('required', message: 'Nama tagihan wajib diisi.')]
    public $nama_tagihan = '';

    #[Validate('required|integer|min:1', message: [
        'required' => 'Total tagihan wajib diisi.',
        'integer' => 'Total tagihan harus berupa angka.',
        'min' => 'Total tagihan minimal 1.',
    ])]
    public $total_tagihan = '';

    public function boot(TagihanServices $services)
    {
        $this->services = $services;
    }

    public function setSiswa(Core $siswa)
    {
        $this->siswa = $siswa->loadMissing('listlolos');
        $this->tgl_terbit = now()->format('Y-m-d');
    }

    public function setModel(Tagihan $tagihan)
    {
        $this->tagihan = $tagihan;
        $this->id_t = $tagihan->id_t;
        $this->tgl_terbit = $tagihan->tgl_terbit;
        $this->nama_tagihan = $tagihan->nama_tagihan;
        $this->total_tagihan = $tagihan->total_tagihan;
    }

    public function store()
    {
        $this->validate();

        $this->services->create(
            $this->only([
                'tgl_terbit', 'nama_tagihan', 'total_tagihan',
            ]),
            $this->siswa
        );
    }

    public function update()
    {
        $this->validate();

        $this->services->edit(
            $this->only([
                'id_t', 'tgl_terbit', 'nama_tagihan', 'total_tagihan',
            ]),
            $this->siswa
        );
    }
}
