<?php

namespace App\Http\Controllers;

use App\Models\Core;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;



class NafudaController extends Controller
{
    public $siswa;
    public $data;
    public function download($nis)
    {
        $siswa = Core::with(['detail'])->where('nis',$nis)->first();
        $qr = base64_encode(QrCode::format('png')->size(100)->generate($siswa->nis));
        
        return Pdf::loadView('nafuda',['siswa' => $siswa,'qr' => $qr])->stream('tes.pdf');
    }

}
