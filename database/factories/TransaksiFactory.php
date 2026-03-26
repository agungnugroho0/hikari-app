<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Tagihan;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransaksiFactory extends Factory
{
    public function definition(): array
    {
        $tagihan = Tagihan::with('tagihansiswa.detail')->inRandomOrder()->first();

        $bayar = fake()->numberBetween(100000, $tagihan->kekurangan_tagihan);

        return [
            'id_tx' => 'TX-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5)),

            'nis' => $tagihan->nis,
            'id_t' => $tagihan->id_t,

            // 🔥 ambil dari detail_siswa
            'nama_lengkap' => $tagihan->tagihansiswa->detail->nama_lengkap ?? 'Siswa',

            'nama_transaksi' => $tagihan->nama_tagihan,
            'tgl_transaksi' => fake()->date(),

            'nominal' => $bayar,
        ];
    }
}