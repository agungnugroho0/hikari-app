<?php

namespace App\Services;

use App\Models\Kelas;
use Illuminate\Support\Facades\DB;

class KelasServices
{
    // public function generateId()
    // {
    //     $prefix = 'S'.date('Ymd');
    //     $terbaru = Kelas::where('id_kelas', 'like', $prefix.'%')->lockForUpdate()->orderBy('id_staff', 'desc')->first();

    //     if ($terbaru) {
    //         $number = (int) substr($terbaru->id_kelas, -3);
    //         $number++;
    //     } else {
    //         $number = 1;
    //     }

    //     return $prefix.str_pad($number, 3, '0', STR_PAD_LEFT);
    // }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            Kelas::create([
                'nama_kelas' => $data['namakelas'],
                'tingkat' => $data['tingkat'],
                'id_pengajar' => $data['pengajar'],
            ]);
        });
    }

    public function edit(array $data)
    {
        return DB::transaction(function () use ($data) {
            $kelas = Kelas::findOrFail($data['id_kelas']);
            $kelas->update([
                'nama_kelas' => $data['namakelas'],
                'tingkat' => $data['tingkat'],
                'id_pengajar' => $data['pengajar'],
            ]);
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $kls = Kelas::findOrFail($id);
            $kls->delete();
        });
    }
}
