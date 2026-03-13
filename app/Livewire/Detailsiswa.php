<?php
namespace App\Livewire;
use App\Models\Core;
use Livewire\Attributes\On;
use Livewire\Component;

class Detailsiswa extends Component
{
    public $siswa = null;
    public $isEditing = false;
    
    #[On('pilih-siswa')]
    public function loadSiswa($nis)
    {
    $this->siswa = Core::with(['detail','list_w'])->where('nis', $nis)->first();
    $this->isEditing = false;
    }

    #[On('edit-siswa')]
    public function editSiswa()
    {
        if ($this->siswa) {
            $this->isEditing = true;
        }
    }

    // Fungsi untuk membatalkan edit
    #[On('batal-edit')]

    public function cancelEdit()
    {
        $this->isEditing = false;
    }

    public function nafuda()
    {
    return redirect()->to('/nafuda/'.$this->siswa->nis);
    }
    
    public function render(){
        return view("detail-siswa");
    }
};