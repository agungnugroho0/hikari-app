<?php

namespace App\Services;

use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingsServices
{
    public function updateukk(array $data)
    {
    return DB::transaction(function () use ($data) {
        $ukk = Settings::findOrFail($data['id_st']);

        $ket = $data['ket']
            ? Carbon::createFromFormat('Y-m-d', $data['ket'])->format('d/m/Y')
            : null;

        $ukk->update([
            'nama_set' => $data['nama_set'], // pastikan ini benar
            'ket' => $ket,
        ]);

        return $ukk;
    });
    }

    public function updatenfd(array $data)
    {
        return DB::transaction(function () use ($data) {
            $nfd = Settings::findOrFail($data['id_st']);

            $ket = $nfd->ket; // default: pakai lama

            // kalau ada file baru
            if (isset($data['ket']) && $data['ket'] instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {

                // hapus file lama (opsional tapi recommended)
                if ($nfd->ket && Storage::disk('public')->exists($nfd->ket)) {
                    Storage::disk('public')->delete($nfd->ket);
                }

                // simpan file baru
                $ket = $data['ket']->store('uploads/nafuda', 'public');
            }

            $nfd->update([
                'nama_set' => $data['nama_set'],
                'ket' => $ket,
            ]);

            return $nfd;
        });
    }

    public function updateKelasSaatIni(array $data)
    {
        return DB::transaction(function () use ($data) {
            $setting = Settings::findOrFail($data['id_st']);

            $setting->update([
                'nama_set' => $data['nama_set'],
                'ket' => $data['ket'],
            ]);

            return $setting;
        });
    }

    // public function updatenfd2(array $data)
    // {
    //     return DB::transaction(function () use ($data) {
    //         $nfd = Settings::findOrFail($data['id_st']);

    //         $ket = $nfd->ket; // default: pakai lama

    //         // kalau ada file baru
    //         if (isset($data['ket']) && $data['ket'] instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {

    //             // hapus file lama (opsional tapi recommended)
    //             if ($nfd->ket && Storage::disk('public')->exists($nfd->ket)) {
    //                 Storage::disk('public')->delete($nfd->ket);
    //             }

    //             // simpan file baru
    //             $ket = $data['ket']->store('uploads/nafuda', 'public');
    //         }

    //         $nfd->update([
    //             'nama_set' => $data['nama_set'],
    //             'ket' => $ket,
    //         ]);

    //         return $nfd;
    //     });
    // }
}
