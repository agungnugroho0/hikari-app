<?php

namespace App\Livewire\Pages;

use App\Models\So;
use App\Services\SoService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Sending Organizer')]
class SendingOrganizer extends Component
{
    public $metode = 'list';

    public $deleteId;

    public $showConfirm = false;

    public $so;

    public $idso;

    public function mode($metode, $id = null)
    {
        $this->metode = $metode;
        if ($metode === 'edit') {
            $this->idso = $id;
        }
    }

    public function confirmDelete($id)
    {
        $this->showConfirm = true;
        $this->deleteId = $id;
    }

    public function deleteso(SoService $service)
    {
        $this->showConfirm = false;
        $service->delete($this->deleteId);
        $this->dispatch('tutup', message: 'Kelas berhasil dihapus!');
    }

    #[On('tutup')]
    public function muat()
    {
        $this->metode = 'list';
        $this->showConfirm = false;
        $this->so = So::get();
    }

    public function mount()
    {
        $this->muat();
    }

    public function render()
    {
        return view('pages.sending-organizer');
    }
}
