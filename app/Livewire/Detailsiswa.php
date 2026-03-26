<?php
namespace App\Livewire;

use App\Models\Core;
use App\Services\SiswaServices;
use Livewire\Attributes\On;
use Livewire\Component;

class Detailsiswa extends Component
{
    public $siswa = null;
    public $isEditing = false;
    public $tx = false;
    public $tagihan = false;
    
    #[On('pilih-siswa')]
    public function loadSiswa($nis)
    {
    $this->siswa = Core::with(['detail', 'kelas', 'list_w', 'listtagihan_siswa', 'listlolos'])->where('nis', $nis)->first();
    $this->isEditing = false;
    $this->tx = false;
    $this->tagihan = false;
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

    public function buattx()
    {
        if (!$this->siswa) {
            return;
        }

        $this->tx = true;
        $this->tagihan = false;
        $this->isEditing = false;
    }

    public function buattagihan()
    {
        if (!$this->siswa) {
            return;
        }

        $this->tagihan = true;
        $this->tx = false;
        $this->isEditing = false;
    }

    #[On('transaksi-ditutup')]
    public function batalTx()
    {
        $this->tx = false;
    }

    #[On('tagihan-ditutup')]
    public function batalTagihan()
    {
        $this->tagihan = false;
    }

    #[On('transaksi-tersimpan')]
    #[On('tagihan-tersimpan')]
    public function refreshSiswa()
    {
        if (!$this->siswa) {
            return;
        }

        $this->siswa = Core::with(['detail', 'kelas', 'list_w', 'listtagihan_siswa', 'listlolos'])->where('nis', $this->siswa->nis)->first();
        $this->tx = false;
        $this->tagihan = false;
    }

    #[On('siswa-deleted')]
    public function clearDeletedSiswa($nis)
    {
        if ($this->siswa && $this->siswa->nis === $nis) {
            $this->siswa = null;
            $this->isEditing = false;
            $this->tx = false;
            $this->tagihan = false;
        }
    }

    public function nafuda()
    {
    return redirect()->to('/nafuda/'.$this->siswa->nis);
    }

    public function unfit(SiswaServices $service)
    {
        if (!$this->siswa || $this->siswa->status !== 'lolos') {
            return;
        }

        $service->unfit($this->siswa->nis);
        $this->refreshSiswa();
        $this->dispatch('tutup', message: 'Status siswa dikembalikan ke siswa.');
    }

    public function getTotalTagihanProperty()
    {
        if (!$this->siswa || !$this->siswa->listtagihan_siswa) {
            return 0;
        }

        return $this->siswa->listtagihan_siswa
            ->where('status_tagihan', '!=', 'lunas')
            ->sum('kekurangan_tagihan');
    }

    
    public function render(){
        return view("detail-siswa");
    }
};
