<?php

namespace App\Services;

use App\Models\Core;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SiswaServices
{
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
}
