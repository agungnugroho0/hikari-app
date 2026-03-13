<?php

namespace App\Livewire\Pages;

use App\Models\Staff as StaffModel;
use App\Services\StaffServices;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.admin')]
class Staff extends Component
{
    public $staff_id;

    public $deleteId;

    public $showConfirm = false;

    public $staff = [];

    public $metode = 'list';

    public function mode($metode, $id = null)
    {
        $this->metode = $metode;
        if ($metode === 'edit') {
            $this->staff_id = $id;
        }
    }

    #[On('tutup')]
    public function muat()
    {
        $this->metode = 'list';
        $this->staff = StaffModel::get();
    }

    public function confirmDelete($id_staff)
    {
        $this->deleteId = $id_staff;
        $this->showConfirm = true;
    }

    public function deletestaff(StaffServices $service)
    {
        $this->showConfirm = false;
        $service->delete($this->deleteId);
        $this->dispatch('tutup', message: 'Staff berhasil dihapus!');
    }

    public function mount()
    {
        $this->muat();
    }

    public function render()
    {
        return view('pages.staff');
    }
}
