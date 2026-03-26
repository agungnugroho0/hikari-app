<?php

namespace App\Http\Controllers;

use App\Models\Core;
use App\Models\Settings;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;



class NafudaController extends Controller
{
    public $siswa;
    public $data;

    public function download($nis)
    {
        $nafuda = Settings::where('nama_set', 'nafuda')->first();
        $path = storage_path('app/public/' . $nafuda->ket);
        $nafuda2 = Settings::where('nama_set', 'nafuda2')->first();
        $path2 = storage_path('app/public/' . $nafuda2->ket);
        $siswa = Core::with(['detail'])->where('nis', $nis)->firstOrFail();
        $qr = base64_encode(
            QrCode::format('svg')
                ->size(160)
                ->margin(1)
                ->generate($siswa->nis)
        );

        return Pdf::loadView('nafuda', [
            'siswa' => $siswa,
            'qr' => $qr,
            'nafuda1' =>$path,
            'nafuda2' =>$path2
        ])->stream('nafuda-' . $siswa->nis . '.pdf');
    }
}
