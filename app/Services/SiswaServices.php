<?php

namespace App\Services;

use App\Models\Core;
use App\Models\DetailSiswa;
use App\Models\Settings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SiswaServices
{
    public function createPublic(array $data): string
    {
        return DB::transaction(function () use ($data) {
            $nis = $this->generateNis();

            Core::query()->create([
                'nis' => $nis,
                'id_kelas' => $data['id_kelas'],
                'status' => 'siswa',
                'foto' => null,
            ]);

            DetailSiswa::query()->create([
                'nis' => $nis,
                'nama_lengkap' => $data['nama_lengkap'],
                'panggilan' => $data['panggilan'],
                'tgl_lahir' => $data['tgl_lahir'],
                'gender' => $data['gender'],
                'tempat_lhr' => $data['tempat_lhr'],
                'alamat' => $data['alamat'],
                'wa' => $data['wa'],
                'wa_wali' => $data['wa_wali'] ?: null,
                'pernikahan' => $data['pernikahan'],
                'agama' => $data['agama'],
            ]);

            return $nis;
        });
    }

    public function updateSiswa(Core $siswa, array $coreData, array $detailData): void
    {
        DB::transaction(function () use ($siswa, $coreData, $detailData) {
            $lockedSiswa = Core::query()
                ->with('detail')
                ->lockForUpdate()
                ->findOrFail($siswa->nis);

            $lockedSiswa->update($coreData);
            $lockedSiswa->detail()->update($detailData);
        });
    }

    public function currentClassId(): ?string
    {
        return Settings::query()
            ->where('nama_set', 'kelas_saat_ini')
            ->value('ket');
    }

    public function delete(string $nis): void
    {
        DB::transaction(function () use ($nis) {
            $siswa = Core::with([
                'detail',
                'list_w',
                'listlolos',
            ])->lockForUpdate()->findOrFail($nis);

            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }

            $siswa->listlolos()?->delete();
            $siswa->list_w()->delete();
            $siswa->detail()?->delete();
            $siswa->update([
                'status' => 'dihapus',
                'foto' => null,
            ]);
        });
    }

    public function unfit(string $nis): void
    {
        DB::transaction(function () use ($nis) {
            $siswa = Core::with(['listlolos'])->lockForUpdate()->findOrFail($nis);

            $idSo = $siswa->listlolos?->id_so;

            if ($idSo) {
                $siswa->listtagihan_siswa()
                    ->where('id_so', $idSo)
                    ->delete();
            }

            $siswa->listlolos()?->delete();

            $siswa->update([
                'status' => 'siswa',
            ]);
        });
    }

    protected function generateNis(): string
    {
        $prefix = now()->format('Ymd');

        $latest = Core::query()
            ->where('nis', 'like', $prefix . '%')
            ->orderByDesc('nis')
            ->lockForUpdate()
            ->first();

        $nextNumber = $latest
            ? ((int) substr($latest->nis, 8)) + 1
            : 1;

        return $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
