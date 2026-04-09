<?php

namespace App\Services;

use App\Models\Kelas;
use Illuminate\Support\Facades\DB;

class KelasServices
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Kelas::create([
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

            return $kelas;
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
