<?php

namespace App\Services;

use App\Models\JobFairModels;
use Illuminate\Support\Facades\DB;

class JobFairServices
{
    public function generateId(){
        $prefix='J'.date('Ymd');
        $terbaru = JobFairModels::where('id_job','like',$prefix.'%')->lockForUpdate()->orderBy('id_job','desc')->first();

        if($terbaru){
            $number = (int) substr($terbaru->id_job,-3);
            $number++;
        }else{
            $number = 1;
        }
        return $prefix. str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function create(array $data){
        return DB::transaction(function() use ($data){
        $data['tgl_wawancara'] = $data['tgl_wawancara'] ?: null;
         JobFairModels::create([
                'id_job' => $this->generateId(),
                'nama_job' => $data['nama_job'],
                'perusahaan' => $data['perusahaan'] ?? null,
                'id_so' => $data['id_so'],
                'tgl_wawancara' => $data['tgl_wawancara'],
                'penempatan' => $data['penempatan'] ?? null,
                'metode' => $data['metode'],
            ]);
        });
    }

    public function update(array $data){
        return DB::transaction(function() use ($data){
            $job = JobFairModels::lockForUpdate()
                ->where('id_job', $data['id_job'])
                ->firstOrFail();

            $job->update([
                'nama_job' => $data['nama_job'],
                'perusahaan' => $data['perusahaan'] ?? null,
                'id_so' => $data['id_so'],
                'tgl_wawancara' => $data['tgl_wawancara'] ?: null,
                'penempatan' => $data['penempatan'] ?? null,
                'metode' => $data['metode'],
            ]);
        });
    }

    public function delete($id){
        return DB::transaction(function() use ($id){
            $job = JobFairModels::with('list_ww')->lockforUpdate()->findOrFail($id);
            $job->list_ww()->delete(); // hapus peserta wawancara
            $job->delete(); //hapus job
        });
    }
}
