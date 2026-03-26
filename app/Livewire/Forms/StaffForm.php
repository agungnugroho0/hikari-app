<?php

namespace App\Livewire\Forms;

use App\Models\Staff;
use App\Services\StaffServices;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class StaffForm extends Form
{
    use WithFileUploads;

    protected StaffServices $services;

    public $id_staff;

    #[Validate('required', message: 'Silahkan Isi Nama Lengkap')]
    public $nama_s;

    #[Validate('required|unique:staff,username', message: ['required' => 'Isi username', 'unique' => 'Harus unik'])]
    public $username;

    #[Validate('required', message: 'Pilih aksesnya')]
    public $akses;

    #[Validate('nullable|image|mimes:jpg,jpeg,png|max:3072', message: [
        'mimes' => 'file harus JPEG,JPG,PNG',
        'max' => 'File ukuran Maksimal 3MB',
    ])] // 3MB Max
    public $foto_s;

    public $foto_lama;

    public function boot(StaffServices $services)
    {
        $this->services = $services;
    }

    public function store()
    {
        $this->validate();
        $this->services->create(
            $this->only([
                'nama_s', 'username', 'akses', 'foto_s',
            ])
        );
    }

        public function setModels($id)
        {
            $staff = Staff::findorfail($id);
            $this->id_staff = $staff->id_staff;
            $this->nama_s = $staff->nama_s;
            $this->username = $staff->username;
            $this->akses = $staff->akses;
            $this->foto_s = $staff->foto_s;
        }

    public function update()
    {
        $this->validate([
            'nama_s' => 'required',
            'akses' => 'required',
        ]);
        $this->services->edit(
            $this->only([
                'id_staff', 'nama_s', 'username', 'akses', 'foto_s',
            ])
        );
    }
}
