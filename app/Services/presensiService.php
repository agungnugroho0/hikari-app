<?php

namespace App\Services;

use App\Models\Absen;

class presensiService
{
    public function generateId()
    {
        $prefix = 'ABS'.date('Ymd');
        $terbaru = Absen::where('id_absen', 'like', $prefix.'%')->lockForUpdate()->orderBy('id_absen', 'desc')->first();

        if ($terbaru) {
            $number = (int) substr($terbaru->id_absen, -3);
            $number++;
        } else {
            $number = 1;
        }

        return $prefix.str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function absen($nis, $status)
    {
        dd($this->generateId(), $nis, $status);
    }
}
