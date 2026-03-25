<?php

namespace App\Services;

use App\Models\Core;
use App\Models\Tagihan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagihanServices
{
    public function generateId()
    {
        do {
            $id = 'T' . now()->format('YmdHis') . Str::upper(Str::random(4));
        } while (Tagihan::where('id_t', $id)->exists());

        return $id;
    }

    public function create(array $data, Core $siswa)
    {
        return DB::transaction(function () use ($data, $siswa) {
            $total = (int) $data['total_tagihan'];

            return Tagihan::create([
                'id_t' => $this->generateId(),
                'nis' => $siswa->nis,
                'id_so' => null,
                'tgl_terbit' => $data['tgl_terbit'],
                'nama_tagihan' => $data['nama_tagihan'],
                'kekurangan_tagihan' => $total,
                'total_tagihan' => $total,
                'status_tagihan' => 'belum',
            ]);
        });
    }

    public function edit(array $data, Core $siswa)
    {
        return DB::transaction(function () use ($data, $siswa) {
            $tagihan = Tagihan::where('id_t', $data['id_t'])
                ->where('nis', $siswa->nis)
                ->lockForUpdate()
                ->firstOrFail();

            $totalBaru = (int) $data['total_tagihan'];
            $sudahDibayar = max(0, (int) $tagihan->total_tagihan - (int) $tagihan->kekurangan_tagihan);
            $sisaBaru = max(0, $totalBaru - $sudahDibayar);

            $tagihan->update([
                'id_so' => null,
                'tgl_terbit' => $data['tgl_terbit'],
                'nama_tagihan' => $data['nama_tagihan'],
                'total_tagihan' => $totalBaru,
                'kekurangan_tagihan' => $sisaBaru,
                'status_tagihan' => $this->resolveStatus($totalBaru, $sisaBaru),
            ]);

            return $tagihan;
        });
    }

    protected function resolveStatus(int $total, int $sisa)
    {
        if ($sisa <= 0) {
            return 'lunas';
        }

        if ($sisa >= $total) {
            return 'belum';
        }

        return 'cicil';
    }
}
