<?php

namespace App\Services;

use App\Models\So;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SoService
{
    public function generateId()
    {
        $prefix = 'S'.date('Ymd');
        // $prefix = 'S';
        $terbaru = So::where('id_so', 'like', $prefix.'%')->lockForUpdate()->orderBy('id_so', 'desc')->first();

        if ($terbaru) {
            $number = (int) substr($terbaru->id_so, -3);
            $number++;
        } else {
            $number = 1;
        }

        return $prefix.str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            $filename = null;
            $path = null;
            if ($data['foto_so']) {
                $filename = Str::slug($data['nama_so']).'.'.$data['foto_so']->getClientOriginalExtension();
                $path = $data['foto_so']->storeAs('foto_so', $filename, 'public');
            }

            So::create([
                'id_so' => $this->generateId(),
                'nama_so' => $data['nama_so'],
                'lokasi' => $data['lokasi'],
                'pj' => $data['pj'],
                'ket' => $data['ket'],
                'foto_so' => $path ?? null,
            ]);
        });
    }

    public function edit(array $data)
    {
        return DB::transaction(function () use ($data) {
            $so = So::findOrfail($data['idso']);
            if (! empty($data['foto_so']) && is_object($data['foto_so'])) {
                // hapus foto lama
                if ($so->foto_so && Storage::disk('public')->exists($so->foto_so)) {
                    Storage::disk('public')->delete($so->foto_so);
                }
                // simpan foto baru
                $filename = Str::uuid().'.'.$data['foto_so']->getClientOriginalExtension();
                $data['foto_so'] = $data['foto_so']->storeAs('foto_so', $filename, 'public');

            } else {
                $data['foto_so'] = $so['foto_so'];
            }

            $so->update([
                'id_so' => $data['idso'],
                'nama_so' => $data['nama_so'],
                'foto_so' => $data['foto_so'],
                'lokasi' => $data['lokasi'],
                'pj' => $data['pj'],
                'ket' => $data['ket'],
            ]);
            unset($data['foto_so']);
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $so = So::findOrFail($id);
            if ($so->foto_so && Storage::disk('public')->exists($so->foto_so)) {
                Storage::disk('public')->delete($so->foto_so);
            }
            $so->delete();
        });

    }
}
