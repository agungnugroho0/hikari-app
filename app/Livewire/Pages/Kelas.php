<?php

namespace App\Livewire\Pages;

use App\Models\Kelas as KelasModel;
use App\Services\KelasServices;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Kelas')]
class Kelas extends Component
{
    public $kelas = [];

    public $guru = [];

    public $metode = 'list';

    public $id_kls;

    public $deleteId;

    public $showConfirm = false;

    public function mode($metode, $id = null)
    {
        $this->metode = $metode;
        if ($metode === 'edit') {
            $this->id_kls = $id;
        }
    }

    #[On('tutup')]
    public function muat()
    {
        $this->metode = 'list';
        $this->kelas = KelasModel::with('guru:id_staff,nama_s')->get();
        $this->showConfirm = false;
    }

    public function confirmDelete($id_kelas)
    {
        $this->deleteId = $id_kelas;
        $this->showConfirm = true;
    }

    public function deletekelas(KelasServices $service)
    {
        $this->showConfirm = false;
        $service->delete($this->deleteId);
        $this->dispatch('tutup', message: 'Kelas berhasil dihapus!');
    }

    public function mount()
    {
        $this->muat();
    }

    public function render()
    {
        return view('pages.kelas');
    }
}
