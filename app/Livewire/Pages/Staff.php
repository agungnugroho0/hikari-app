<?php

namespace App\Livewire\Pages;

use App\Models\Staff as StaffModel;
use App\Services\StaffServices;
use Illuminate\Support\Facades\Hash;
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
        if ($metode === 'edit' && $id) {
            $staff = StaffModel::query()
                ->where('id_staff', $id)
                ->where('akses', '!=', 'dev')
                ->firstOrFail();

            $this->staff_id = $staff->id_staff;
            $this->metode = $metode;

            return;
        }

        $this->metode = $metode;
    }

    #[On('tutup')]
    public function muat()
    {
        $this->metode = 'list';
        $this->staff = StaffModel::query()
            ->where('akses', '!=', 'dev')
            ->get();
    }

    public function confirmDelete($id_staff)
    {
        StaffModel::query()
            ->where('id_staff', $id_staff)
            ->where('akses', '!=', 'dev')
            ->firstOrFail();

        $this->deleteId = $id_staff;
        $this->showConfirm = true;
    }

    public function deletestaff(StaffServices $service)
    {
        $this->showConfirm = false;
        $service->delete($this->deleteId);
        $this->dispatch('tutup', message: 'Staff berhasil dihapus!');
    }

    public function resetPassword(string $id_staff): void
    {
        $staff = StaffModel::query()
            ->where('id_staff', $id_staff)
            ->where('akses', '!=', 'dev')
            ->firstOrFail();

        $staff->update([
            'password' => Hash::make('123456'),
        ]);

        $this->dispatch('tutup', message: 'Password staff berhasil direset ke 123456.');
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
