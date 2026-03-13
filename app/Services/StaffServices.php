<?php

namespace App\Services;

use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class StaffServices
{
    public function generateId()
    {
        // $prefix = 'S'.date('Ymd');
        $prefix = 'S';
        $terbaru = Staff::where('id_staff', 'like', $prefix.'%')->lockForUpdate()->orderBy('id_staff', 'desc')->first();

        if ($terbaru) {
            $number = (int) substr($terbaru->id_job, -3);
            $number++;
        } else {
            $number = 1;
        }

        return $prefix.str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            Staff::create([
                // 'id_staff' => $this->generateId(),
                'username' => $data['username'],
                'nama_s' => $data['nama_s'],
                'akses' => $data['akses'],
                'foto_s' => $data['foto_s'] ? $data['foto_s']->store('staff', 'public') : null,
                'password' => Hash::make('123456'),
            ]);
        });
    }

    public function edit(array $data)
    {
        return DB::transaction(function () use ($data) {
            $staff = Staff::findOrFail($data['id_staff']);

            // cek apakah ada upload foto baru
            if (isset($data['foto_s']) && $data['foto_s'] instanceof TemporaryUploadedFile) {

                // hapus foto lama
                if ($staff->foto_s && Storage::disk('public')->exists($staff->foto_s)) {
                    Storage::disk('public')->delete($staff->foto_s);
                }

                // simpan foto baru
                $data['foto_s'] = $data['foto_s']->store('staff', 'public');

            } else {
                // kalau tidak upload foto baru, pakai foto lama
                $data['foto_s'] = $staff->foto_s;
            }
            $staff->update([
                'username' => $data['username'],
                'nama_s' => $data['nama_s'],
                'akses' => $data['akses'],
                'foto_s' => $data['foto_s'],
            ]);
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $staff = Staff::findOrFail($id);
            // dd($staff);
        });
    }
}
