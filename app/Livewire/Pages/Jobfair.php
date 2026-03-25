<?php
namespace App\Livewire\Pages;

use App\Models\So;
use App\Models\ListWawancara;
use App\Services\JobFairServices;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')] 
#[Title('Job Order')]
class Jobfair extends Component
{

    public $bukaform = false;
    public $so;
    public $editjob = false;
    public $idjob;
    public $deleteId;
    public $showConfirm = false;
    public $tambahpesertaform = false;
    public $lolosform = false;

    protected function loadSoData(): void
    {
        $this->so = So::whereHas('list_job')
            ->with(['list_job.list_ww.corelist.detail'])
            ->get();
    }


    public function bukaforms()
    {
        $this->lolosform = false;
        $this->bukaform = true;
        $this->editjob = false;
        $this->tambahpesertaform = false;

    }
    public function tambahpeserta($id_job)
    {
        $this->lolosform = false;
        $this->bukaform = false;
        $this->editjob = false;
        $this->tambahpesertaform = true;
        $this->dispatch('tambahpeserta',$id_job);

    }

    #[On('tutupforms')]
    public function tutupforms()
    {
        $this->bukaform = false;
        $this->editjob = false;
        $this->tambahpesertaform = false;
        $this->lolosform = false;

    }

    #[On('jobfair-updated')]
    public function refreshData()
    {
        $this->loadSoData();
    }

    public function editjobs($id_job)
    {
        $this->editjob = true;
        $this->bukaform = false;
        $this->tambahpesertaform = false;
        $this->lolosform = false;
        $this->dispatch('loadEditJob',$id_job);
    }

    public function confirmDelete($id_job)
    {
        $this->deleteId = $id_job;
        $this->showConfirm = true;
    }

    public function deletejobs(JobFairServices $service)
    {

        $this->showConfirm = false;
        $service->delete($this->deleteId);
        $this->loadSoData();
        $this->dispatch('tutupforms',message:'Berhasil hapus job');

    }

    public function gagal($nis, $id_job)
    {
        $deleted = ListWawancara::where('nis', $nis)
            ->where('id_job', $id_job)
            ->delete();

        if ($deleted > 0) {
            $this->loadSoData();
            $this->dispatch('tutupforms', message: 'Peserta berhasil dihapus');
            return;
        }

        $this->dispatch('tutupforms', message: 'Peserta tidak ditemukan');
    }

    public function lolos($nis,$id_job)
    {
        $this->lolosform = true;
        $this->editjob = false;
        $this->bukaform = false;
        $this->tambahpesertaform = false;
        $this->dispatch('formlolos',['nis'=> $nis, 'id_job'=> $id_job]);
    }

    public function mount()
    {
        $this->loadSoData();
    }
    public function render()
    {
        return view('pages.jobfair');
    }
};
