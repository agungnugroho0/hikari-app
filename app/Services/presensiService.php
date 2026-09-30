<?php

namespace App\Services;

use App\Models\Absen;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class presensiService
{
    public function __construct(
        private readonly FonnteWhatsAppService $whatsAppService
    ) {}

    public function generateId(?string $date = null): string
    {
        $prefix = 'ABS'.Carbon::parse($date ?? now())->format('Ymd');
        $number = Absen::query()
            ->where('id_absen', 'like', $prefix.'%')
            ->lockForUpdate()
            ->pluck('id_absen')
            ->map(fn (string $id): int => (int) substr($id, strlen($prefix)))
            ->max() + 1;

        return $prefix.str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function absen($nis, $status)
    {
        $data = ['nis' => $nis, 'status' => $status];

        $absen = DB::transaction(function () use ($data) {
            $sudah = Absen::where('nis', $data['nis'])
                ->whereDate('tgl', date('Y-m-d'))
                ->exists();

            if ($sudah) {
                return false;
            }

            return Absen::create([
                'id_absen' => $this->generateId(),
                'nis' => $data['nis'],
                'tgl' => date('Y-m-d'),
                'ket' => $data['status'],
            ]);
        });

        if ($absen && strtolower($status) === 'a') {
            $this->whatsAppService->sendAlfaNotification($absen);
        }

        return $absen;
    }
}
