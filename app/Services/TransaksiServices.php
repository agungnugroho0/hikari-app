<?php

namespace App\Services;

use App\Models\Core;
use App\Models\Tagihan;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransaksiServices
{
    public function generateId()
    {
        do {
            $id = 'TX-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5));
        } while (Transaksi::where('id_tx', $id)->exists());

        return $id;
    }

    public function create(array $data, Core $siswa)
    {
        return DB::transaction(function () use ($data, $siswa) {
            $tagihan = Tagihan::where('id_t', $data['id_t'])
                ->where('nis', $siswa->nis)
                ->lockForUpdate()
                ->firstOrFail();

            $nominal = (int) $data['nominal'];

            if ($nominal > (int) $tagihan->kekurangan_tagihan) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'nominal' => 'Nominal melebihi sisa tagihan.',
                ]);
            }

            Transaksi::create([
                'id_tx' => $this->generateId(),
                'nis' => $siswa->nis,
                'id_t' => $tagihan->id_t,
                'nama_lengkap' => optional($siswa->detail)->nama_lengkap ?? 'Siswa',
                'tgl_transaksi' => $data['tgl_transaksi'],
                'nama_transaksi' => $tagihan->nama_tagihan,
                'nominal' => $nominal,
            ]);

            $sisa = (int) $tagihan->kekurangan_tagihan - $nominal;

            $tagihan->update([
                'kekurangan_tagihan' => $sisa,
                'status_tagihan' => $this->resolveStatus($tagihan, $sisa),
            ]);
        });
    }

    public function edit(array $data, ?Core $siswa = null)
    {
        return DB::transaction(function () use ($data, $siswa) {
            $transaksi = Transaksi::where('id_tx', $data['id_tx'])
                ->lockForUpdate()
                ->firstOrFail();

            $tagihanLama = Tagihan::where('id_t', $transaksi->id_t)->lockForUpdate()->firstOrFail();
            $targetNis = $siswa?->nis ?? $transaksi->nis;
            $tagihanBaru = Tagihan::where('id_t', $data['id_t'])
                ->where('nis', $targetNis)
                ->lockForUpdate()
                ->firstOrFail();

            $nominalLama = (int) $transaksi->nominal;
            $nominalBaru = (int) $data['nominal'];

            $saldoTersedia = (int) $tagihanBaru->kekurangan_tagihan;

            if ($tagihanLama->is($tagihanBaru)) {
                $saldoTersedia += $nominalLama;
            }

            if ($nominalBaru > $saldoTersedia) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'nominal' => 'Nominal melebihi sisa tagihan.',
                ]);
            }

            $transaksi->update([
                'id_t' => $tagihanBaru->id_t,
                'tgl_transaksi' => $data['tgl_transaksi'],
                'nama_transaksi' => $tagihanBaru->nama_tagihan,
                'nominal' => $nominalBaru,
            ]);

            if ($tagihanLama->is($tagihanBaru)) {
                $sisaFinal = ((int) $tagihanLama->kekurangan_tagihan + $nominalLama) - $nominalBaru;

                $tagihanLama->update([
                    'kekurangan_tagihan' => $sisaFinal,
                    'status_tagihan' => $this->resolveStatus($tagihanLama, $sisaFinal),
                ]);

                return;
            }

            $sisaLama = (int) $tagihanLama->kekurangan_tagihan + $nominalLama;
            $tagihanLama->update([
                'kekurangan_tagihan' => $sisaLama,
                'status_tagihan' => $this->resolveStatus($tagihanLama, $sisaLama),
            ]);

            $sisaBaru = (int) $tagihanBaru->kekurangan_tagihan - $nominalBaru;
            $tagihanBaru->update([
                'kekurangan_tagihan' => $sisaBaru,
                'status_tagihan' => $this->resolveStatus($tagihanBaru, $sisaBaru),
            ]);
        });
    }

    protected function resolveStatus(Tagihan $tagihan, int $sisa)
    {
        if ($sisa <= 0) {
            return 'lunas';
        }

        if ($sisa >= (int) $tagihan->total_tagihan) {
            return 'belum';
        }

        return 'cicil';
    }
}
