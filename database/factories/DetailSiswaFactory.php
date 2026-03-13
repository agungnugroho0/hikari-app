<?php

namespace Database\Factories;

use App\Models\Core;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailSiswa>
 */
class DetailSiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = fake()->name();
        return [
            // 'nis'=> Core::factory(),
            // 'nis'=> Core::factory()->create()->nis,
            'nama_lengkap'=>$nama,
            'panggilan'=> Str::afterLast($nama, ' '),
            'tgl_lahir' => fake()->date(),
            'gender'=>fake()->randomElement(['L','P']),
            'tempat_lhr' => fake()->city(),
            'alamat'=> fake()->address(),
            'wa'=> fake()->numerify('08##########'),
            'wa_wali'=> fake()->numerify('08##########'),
            'pernikahan' =>fake()->word()
        ];
    }
}
