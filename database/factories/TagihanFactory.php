<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Core;
use App\Models\So;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagihanFactory extends Factory
{
    public function definition(): array
    {
        $total = fake()->numberBetween(1000000, 5000000);
        $dibayar = fake()->numberBetween(0, $total);

        return [
            'id_t' => 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5)),

            'nis' => Core::inRandomOrder()->value('nis'),
            'id_so' => So::inRandomOrder()->value('id_so'),

            'tgl_terbit' => fake()->date(),
            'nama_tagihan' => fake()->randomElement([
                'Biaya Masuk',
                'SPP',
                'Seragam',
                'Ujian',
            ]),

            'total_tagihan' => $total,
            'kekurangan_tagihan' => $total - $dibayar,

            'status_tagihan' => $dibayar == 0 
                ? 'belum'
                : ($dibayar < $total ? 'cicil' : 'lunas'),
        ];
    }
}