<?php

namespace App\Services;

use App\Models\Staff;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class StaffServices
{
    public function create(array $data)
    {
        $fotoPath = $data['foto_s'] ? $data['foto_s']->store('staff', 'public') : null;

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            try {
                return DB::transaction(function () use ($data, $fotoPath) {
                    return Staff::create([
                        'username' => $data['username'],
                        'nama_s' => $data['nama_s'],
                        'no' => $data['no'],
                        'akses' => $data['akses'],
                        'foto_s' => $fotoPath,
                        'password' => Hash::make('123456'),
                    ]);
                });
            } catch (QueryException $exception) {
                if (! $this->isDuplicatePrimaryKey($exception) || $attempt === 3) {
                    throw $exception;
                }
            }
        }
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
                'no' => $data['no'],
                'akses' => $data['akses'],
                'foto_s' => $data['foto_s'],
            ]);

            return $staff;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $staff = Staff::findOrFail($id);
            if ($staff->foto_s && Storage::disk('public')->exists($staff->foto_s)) {
                Storage::disk('public')->delete($staff->foto_s);
            }
            $staff->delete();
        });
    }

    protected function isDuplicatePrimaryKey(QueryException $exception): bool
    {
        return ($exception->errorInfo[1] ?? null) === 1062
            && str_contains($exception->getMessage(), 'PRIMARY');
    }
}
