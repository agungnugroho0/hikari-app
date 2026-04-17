<?php

namespace App\Http\Controllers;

use App\Models\Core;
use App\Models\Settings;

class NafudaController extends Controller
{
    public function download($nis)
    {
        if (! class_exists(\TCPDF::class)) {
            require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
        }

        $nafuda = Settings::where('nama_set', 'nafuda')->firstOrFail();
        $nafuda2 = Settings::where('nama_set', 'nafuda2')->firstOrFail();
        $siswa = Core::with(['detail'])->where('nis', $nis)->firstOrFail();
        $backgroundLeft = storage_path('app/public/'.$nafuda->ket);
        $backgroundRight = storage_path('app/public/'.$nafuda2->ket);
        $panggilan = trim((string) data_get($siswa, 'detail.panggilan', ''));
        $nama = trim((string) data_get($siswa, 'nama', $siswa->nis));

        $fontPath = public_path('fonts/Naganoshi.ttf');
        // $fontFamily = 'kozminproregular';

        // if (is_file($fontPath)) {
        //     $registeredFont = \TCPDF_FONTS::addTTFfont($fontPath, 'TrueTypeUnicode', '', 96);
        //     if ($registeredFont) {
        //         $fontFamily = $registeredFont;
        //     }
        // }

        if (ob_get_length()) {
            ob_end_clean();
        }

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(config('app.name'));
        $pdf->SetAuthor(config('app.name'));
        $pdf->SetTitle('nafuda-'.$siswa->nis);
        $pdf->SetSubject('Nafuda');
        $pdf->SetKeywords('nafuda, pdf');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setLanguageArray([
            'a_meta_charset' => 'UTF-8',
            'a_meta_dir' => 'ltr',
            'a_meta_language' => 'ja',
            'w_page' => 'page',
        ]);
        $pdf->AddPage();

        if (is_file($backgroundLeft)) {
            $pdf->Image($backgroundLeft, 14.92, 11.368, 90, 55, '', '', '', false, 150, '', false, false, 0, false, false, false);
        }

        if (is_file($backgroundRight)) {
            $pdf->Image($backgroundRight, 104.92, 11.368, 90, 55, '', '', '', false, 150, '', false, false, 0, false, false, false);
        }

        $qrStyle = [
            'border' => 0,
            'padding' => 0,
            'fgcolor' => [0, 0, 0],
            'bgcolor' => [255, 255, 255],
        ];

        $pdf->write2DBarcode($siswa->nis, 'QRCODE,H', 18, 29, 17, 17, $qrStyle, 'N');
        $pdf->write2DBarcode($siswa->nis, 'QRCODE,H', 110, 29, 17, 17, $qrStyle, 'N');

        if ($panggilan !== '') {
            $pdf->SetFont($fontFamily, '', 28);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(37, 29);
            $pdf->MultiCell(70, 12, $panggilan, 0, 'L');

            $pdf->SetFont($fontFamily, '', 28);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetXY(129, 29);
            $pdf->MultiCell(70, 12, $panggilan, 0, 'L');
        }

        return response($pdf->Output('nafuda-'.$siswa->nis.'.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.($nama !== '' ? $nama : 'nafuda-'.$siswa->nis).'.pdf"',
        ]);
    }
}
