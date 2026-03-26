<?php

namespace App\Http\Controllers;

use App\Models\Core;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingStatementController extends Controller
{
    public function download($nis)
    {
        $siswa = Core::with([
            'detail',
            'kelas',
            'listtagihan_siswa' => function ($query) {
                $query->orderBy('tgl_terbit')->orderBy('id_t');
                },
                'listtagihan_siswa.listtx' => function ($query) {
                    $query->orderBy('tgl_transaksi')->orderBy('id_tx');
                    },
                    ])->where('nis', $nis)->firstOrFail();
                    
        $noDokumen = 'BS/' . date('Ymd') . '/' . $siswa->nis. str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $tagihanList = $siswa->listtagihan_siswa;
        $totalTagihan = $tagihanList->sum('total_tagihan');
        $totalTerbayar = $tagihanList->sum(function ($tagihan) {
            return $tagihan->total_tagihan - $tagihan->kekurangan_tagihan;
        });
        $totalSisa = $tagihanList->sum('kekurangan_tagihan');

        $filename = 'billing-statement-' . $siswa->nis . '.pdf';

        return Pdf::loadView('billing-statement', [
            'siswa' => $siswa,
            'tagihanList' => $tagihanList,
            'totalTagihan' => $totalTagihan,
            'totalTerbayar' => $totalTerbayar,
            'totalSisa' => $totalSisa,
            'nodoc' => $noDokumen
        ])->stream($filename);
    }
}
