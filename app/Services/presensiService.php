<?php

namespace App\Services;

use App\Models\Absen;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\Clock\now;

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
        $data = ['nis'=>$nis,'status'=>$status];
        
        return DB::transaction(function() use($data) {

                $sudah = Absen::where('nis',$data['nis'])
                    ->whereDate('tgl', date('Y-m-d'))
                    ->exists();

                if ($sudah) {
                    return false; // ❌ sudah absen
                }
                Absen::create([
                    'id_absen' => $this->generateId(),
                    'nis' => $data['nis'],
                    'tgl' => date('Y-m-d'),
                    'ket' => $data['status']
                ]);
            
        });
    }
}
