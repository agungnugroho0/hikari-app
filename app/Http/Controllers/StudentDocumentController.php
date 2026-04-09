<?php

namespace App\Http\Controllers;

use App\Models\Core;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class StudentDocumentController extends Controller
{
    public function download(Request $request, string $type, string $nis): Response
    {
        $document = $this->resolveDocument($type, $request);

        abort_if($document === null, 404);

        $siswa = Core::query()
            ->with(['detail', 'kelas.pengajar'])
            ->where('nis', $nis)
            ->firstOrFail();

        $this->authorizeGuruDocumentAccess($type, $siswa);

        $filename = sprintf('%s-%s.pdf', $document['slug'], $siswa->nis);
        $documentNumber = sprintf(
            '%s/HIKARI/%s/%s',
            $document['number_code'],
            now()->format('Ymd'),
            $siswa->nis
        );

        return Pdf::loadView($document['view'] ?? 'documents.student-letter', [
            'siswa' => $siswa,
            'document' => $document,
            'documentNumber' => $documentNumber,
            'issuedDate' => now(),
        ])->stream($filename);
    }

    protected function resolveDocument(string $type, Request $request): ?array
    {
        $documents = [
            'sp1' => [
                'slug' => 'sp1',
                'number_code' => 'SP1',
                'title' => 'Surat Peringatan 1',
                'subject' => 'Peringatan tahap pertama',
                'body' => 'Surat ini diberikan sebagai peringatan pertama agar siswa lebih disiplin, menjaga kehadiran, dan menaati tata tertib yang berlaku di lingkungan pelatihan.',
                'signatures' => [
                    ['label' => 'Admin LPK', 'name' => 'Admin LPK Hikari Gakkou'],
                    ['label' => 'Orang Tua/Wali', 'name' => '_____________________'],
                    ['label' => 'Wali Kelas', 'name' => null],
                ],
            ],
            'sp2' => [
                'slug' => 'sp2',
                'number_code' => 'SP2',
                'title' => 'Surat Peringatan 2',
                'subject' => 'Peringatan tahap kedua kepada siswa.',
                'body' => 'Surat ini diberikan sebagai tindak lanjut dari peringatan sebelumnya. Siswa diminta segera memperbaiki sikap, kedisiplinan, dan kepatuhan terhadap aturan lembaga.',
                'signatures' => [
                    ['label' => 'Wali Kelas', 'name' => null],
                    ['label' => 'Orang Tua/Wali', 'name' => '_____________________'],
                    ['label' => 'Siswa', 'name' => null],
                    ['label' => 'Admin LPK', 'name' => 'Admin LPK Hikari Gakkou', 'use_logo' => true],
                ],
            ],
            'sp3' => [
                'slug' => 'sp3',
                'number_code' => 'SP3',
                'title' => 'Surat Keputusan Pengeluaran Siswa',
                'subject' => 'Keputusan pengeluaran siswa dari lembaga.',
                'body' => 'Berdasarkan hasil evaluasi kedisiplinan, kepatuhan terhadap tata tertib, serta pertimbangan administrasi lembaga, siswa tersebut ditetapkan untuk dikeluarkan dari kegiatan pelatihan di LPK Hikari Gakkou terhitung sejak surat ini diterbitkan.',
                'signatures' => [
                    ['label' => 'Kepala LPK', 'name' => 'Mohammad Sanan'],
                    ['label' => 'Admin LPK', 'name' => 'Admin LPK Hikari Gakkou'],
                    ['label' => 'Wali Kelas', 'name' => null],
                    ['label' => 'Wali Murid', 'name' => null],
                ],
            ],
            'cuti' => [
                'slug' => 'pengajuan-cuti',
                'number_code' => 'CUTI',
                'title' => 'Surat Pengajuan Cuti Siswa',
                'subject' => 'Pengajuan cuti siswa untuk diproses oleh admin.',
                'body' => 'Melalui surat ini, siswa mengajukan cuti sementara dari kegiatan pelatihan. Pengajuan ini diajukan untuk ditinjau dan ditetapkan lebih lanjut oleh pihak admin/pengelola lembaga.',
                'signatures' => [
                    ['label' => 'Siswa', 'name' => null],
                    ['label' => 'Admin LPK', 'name' => 'Admin LPK Hikari Gakkou'],
                ],
            ],
            'rekomendasi-paspor' => [
                'slug' => 'rekomendasi-paspor',
                'number_code' => 'SRP',
                'title' => 'Surat Rekomendasi Pembuatan Paspor',
                'subject' => 'Rekomendasi pembuatan paspor untuk keperluan bekerja di luar negeri.',
                'recipient' => 'Kepala Kantor Imigrasi',
                'body' => 'Melalui surat ini kami merekomendasikan yang bersangkutan untuk melakukan pembuatan paspor yang akan digunakan sebagai kelengkapan administrasi bekerja di luar negeri. Berdasarkan data yang tercatat di LPK Hikari Gakkou, yang bersangkutan merupakan peserta yang sedang dipersiapkan untuk penempatan kerja di luar negeri.',
                'view' => 'documents.passport-recommendation',
                'signatures' => [
                    ['label' => 'Pimpinan LPK Hikari Gakkou', 'name' => "Mohamad San'an"],
                ],
            ],
        ];

        if ($type === 'pengambilan-dokumen') {
            return $this->buildDocumentPickupLetter($request);
        }

        return $documents[$type] ?? null;
    }

    protected function buildDocumentPickupLetter(Request $request): ?array
    {
        $documentName = trim((string) $request->query('document_name', ''));

        if ($documentName === '') {
            return null;
        }

        $documentLabel = Str::title(Str::lower($documentName));
        $documentSlug = Str::slug($documentName);

        return [
            'slug' => 'pengambilan-dokumen-'.($documentSlug !== '' ? $documentSlug : 'dokumen'),
            'number_code' => 'SPDOK',
            'title' => 'Surat Pengambilan Dokumen',
            'subject' => 'Pengambilan dokumen '.$documentLabel.'.',
            'recipient' => 'Yang Berkepentingan',
            'body' => 'Melalui surat ini diterangkan bahwa siswa tersebut melakukan pengambilan dokumen berupa '.$documentLabel.'. Surat ini dibuat sebagai bukti pengambilan dokumen dari LPK Hikari Gakkou.',
            'signatures' => [
                ['label' => 'Penerima', 'name' => '_____________________'],
                ['label' => 'Admin LPK', 'name' => 'Admin LPK Hikari Gakkou'],
            ],
        ];
    }

    protected function authorizeGuruDocumentAccess(string $type, Core $siswa): void
    {
        $user = Auth::user();

        if (!$user || $user->akses !== 'guru') {
            return;
        }

        abort_unless(in_array($type, ['sp1', 'sp2'], true), 403);
        abort_unless($siswa->kelas?->id_pengajar === $user->id_staff, 403);
    }
}
