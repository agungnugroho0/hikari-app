<?php

namespace App\Services;

use App\Models\Core;
use App\Models\ListLolos;
use App\Models\So;
use App\Models\Tagihan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class WawancaraServices
{
    public function generateId($p, $model, $kolom)
    {
        $prefix = $p.date('Ymd');
        $terbaru = $model::where($kolom, 'like', $prefix.'%')->lockForUpdate()->orderBy($kolom, 'desc')->first();
        if ($terbaru) {
            $number = (int) substr($terbaru->$kolom, -3);
            $number++;
        } else {
            $number = 1;
        }

        return $prefix.str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function nextId($id)
    {
        $prefix = substr($id, 0, -3);
        $num = (int) substr($id, -3) + 1;

        return $prefix.str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    public function create(array $data)
    {

        return DB::transaction(function () use ($data) {
            $core = Core::where('nis', $data['nis'])->lockForUpdate()->firstOrFail();
            $so = So::with('list_job')->where('id_so', '=', $data['id_so'])
                ->whereHas('list_job', function (Builder $q) use ($data) {
                    $q->where('id_job', '=', $data['id_job']);
                })
                ->lockforupdate()->firstorFail();
            // $so = So::with([
            //     'list_job' => function ($q) use ($data) {
            //         $q->where('id_job', $data['id_job']);
            //     },
            // ])
            //     ->where('id_so', $data['id_so'])
            //     ->lockForUpdate()
            // ->first();

            $id_lolos = $this->generateId('L', ListLolos::class, 'id_lolos');
            $id_t = $this->generateId('T', Tagihan::class, 'id_t');
            $id_t2 = $this->nextId($id_t);

            // input loglolos
            ListLolos::create(
                [
                    'nis' => $data['nis'],
                    'id_lolos' => $id_lolos,
                    'id_so' => $data['id_so'],
                    'tgl_lolos' => $data['tgl_lolos'],
                    'nama_job' => $so->list_job->first()->nama_job,
                    'nama_perusahaan' => $so->list_job->first()->perusahaan,
                ]
            );

            // input tagihan
            Tagihan::create(
                [
                    'id_t' => $id_t,
                    'nis' => $data['nis'],
                    'id_so' => $data['id_so'],
                    'tgl_terbit' => now()->toDateString(),
                    'nama_tagihan' => 'Tagihan Hikari',
                    'kekurangan_tagihan' => $data['tagihan'],
                    'total_tagihan' => $data['tagihan'],
                    'status_tagihan' => 'Belum Lunas',
                ]
            );
            Tagihan::create(
                [
                    'id_t' => $id_t2,
                    'nis' => $data['nis'],
                    'id_so' => $data['id_so'],
                    'tgl_terbit' => now()->toDateString(),
                    'nama_tagihan' => 'Tagihan SO '.$so->nama_so,
                    'kekurangan_tagihan' => $data['tagihan_so'],
                    'total_tagihan' => $data['tagihan_so'],
                    'status_tagihan' => 'Belum Lunas',
                ]
            );

            $core->update(
                ['status' => 'lolos']
            );

            // hapus list_w
            $job = $so->list_job->first();
            $list = $job->list_ww()
                ->where('nis', $data['nis'])
                ->first();
            $list?->delete();
        });
    }
}
