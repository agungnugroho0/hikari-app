<?php

namespace App\Services;

use App\Models\Absen;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteWhatsAppService
{
    public function sendAlfaNotification(Absen $absen): bool
    {
        $token = config('services.fonnte.token');

        if (blank($token)) {
            Log::warning('Fonnte token belum diatur, notifikasi alfa tidak dikirim.', [
                'id_absen' => $absen->id_absen,
                'nis' => $absen->nis,
            ]);

            return false;
        }

        $absen->loadMissing(['siswa.detail', 'siswa.kelas.guru']);

        $target = $absen->siswa?->detail?->wa_wali;

        if (blank($target)) {
            Log::warning('Nomor WhatsApp wali siswa kosong, notifikasi alfa tidak dikirim.', [
                'id_absen' => $absen->id_absen,
                'nis' => $absen->nis,
            ]);

            return false;
        }

        try {
            $response = Http::asForm()
                ->withHeaders(['Authorization' => $token])
                ->timeout((int) config('services.fonnte.timeout', 15))
                ->post(config('services.fonnte.endpoint'), [
                    'target' => (string) $target,
                    'message' => $this->buildAlfaMessage($absen),
                    'countryCode' => config('services.fonnte.country_code', '62'),
                ])
                ->throw()
                ->json();

            if (! (bool) data_get($response, 'status')) {
                Log::warning('Fonnte menolak pengiriman notifikasi alfa.', [
                    'id_absen' => $absen->id_absen,
                    'nis' => $absen->nis,
                    'response' => $response,
                ]);

                return false;
            }

            return true;
        } catch (RequestException $exception) {
            Log::error('Gagal mengirim notifikasi alfa melalui Fonnte.', [
                'id_absen' => $absen->id_absen,
                'nis' => $absen->nis,
                'response' => $exception->response?->json(),
                'message' => $exception->getMessage(),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Gagal mengirim notifikasi alfa melalui Fonnte.', [
                'id_absen' => $absen->id_absen,
                'nis' => $absen->nis,
                'message' => $exception->getMessage(),
            ]);
        }

        return false;
    }

    private function buildAlfaMessage(Absen $absen): string
    {
        $namaSiswa = $absen->siswa?->detail?->nama_lengkap ?? $absen->nis;
        $waliKelas = $absen->siswa?->kelas?->guru;
        $namaWaliKelas = $waliKelas?->nama_s ?? '-';
        $nomorWaliKelas = $waliKelas?->no ?? '-';
        $tanggalAbsen = Carbon::parse($absen->tgl)
            ->locale('id')
            ->translatedFormat('l, d F Y');

        return "Assalamualaikum, Yth. Bapak/Ibu Wali Murid, Saudara {$namaSiswa} tidak hadir (A) pada tanggal {$tanggalAbsen}. Mohon untuk menghubungi wali kelas {$namaWaliKelas} di nomor {$nomorWaliKelas}. Terima kasih.\n\n\u{2139}\u{FE0F} Dimohon untuk tidak membalas pesan ini, karena ini adalah pesan otomatis dari sistem presensi sekolah.";
    }
}
