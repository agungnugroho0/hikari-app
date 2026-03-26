<?php

namespace App\Livewire\Forms;

use App\Services\JobFairServices;
use App\Models\JobFairModels;
use App\Models\ListWawancara;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class JobfairForm extends Form
{
    protected JobFairServices $service;
    
    public $perusahaan ='';
    public $tgl_wawancara ='';
    public $penempatan ='';
    public $job ='';
    public $wawancara;
    public $id_job ='';
    public $siswa = []; //untuk menyimpan beberapa siswa

    #[Validate('required',message:'Wajib isi nama job')]
    public $nama_job ='';

    #[Validate('required',message:'Wajib isi nama SO')]
    public $id_so ='';

    #[Validate('required',message:'Wajib pilih metode seleksi')]
    public $metode ='';


    public function boot(JobFairServices $service)
    {
        $this->service = $service;
    }


    public function generateid2(){
        $prefix='W'.date('Ymd');
        $terbaru = ListWawancara::where('id_list','like',$prefix.'%')->lockForUpdate()->orderBy('id_list','desc')->first();

        if($terbaru){
            $number = (int) substr($terbaru->id_list,-4);
            $number++;
        }else{
            $number = 1;
        }
        return $prefix. str_pad($number, 4, '0', STR_PAD_LEFT);
    }

public function store(){
    $this->validate();
    $this->service->create(
        $this->only([
        'nama_job',
        'perusahaan',
        'id_so',
        'tgl_wawancara',
        'penempatan',
        'metode'
        ])
    );
}

public function setModels($id){
    $job = JobFairModels::findOrFail($id);
    $this->job = $job;
    $this->id_job = $job->id_job;
    $this->nama_job = $job->nama_job;
    $this->perusahaan = $job->perusahaan;
    $this->id_so = $job->id_so;
    $this->tgl_wawancara = $job->tgl_wawancara;
    $this->penempatan = $job->penempatan;
    $this->metode = $job->metode;

}

public function update()
{
    $this->validate();
    $this->service->update(
    $this->only(
        [
        'id_job',
        'nama_job',
        'perusahaan',
        'id_so',
        'tgl_wawancara',
        'penempatan',
        'metode'
        ]
    )
    );
}

public function storePeserta(){
    $this->validate([
        'siswa' => 'required|array|min:1',
    ], [
        'siswa.required' => 'Pilih minimal satu peserta.',
        'siswa.array' => 'Format peserta tidak valid.',
        'siswa.min' => 'Pilih minimal satu peserta.',
    ]);

    DB::transaction(function(){
        foreach($this->siswa as $nis){
            $sudahAda = ListWawancara::where('id_job', $this->id_job)
                ->where('nis', $nis)
                ->exists();

            if ($sudahAda) {
                continue;
            }

            $id = $this->generateid2();
            $this->wawancara = ListWawancara::create([
                'id_job' => $this->id_job,
                'nis' => $nis,
                'id_list' => $id
            ]);
        }

    });
    
}
}
