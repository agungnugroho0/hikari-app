<?php

namespace Database\Factories;

use App\Models\Core;
use App\Models\Kelas;
use App\Models\DetailSiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Core>
 */
class CoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'nis'      => now()->format('Ymd') . fake()->unique()->numberBetween(000, 999),
        // 'id_kelas' => Kelas::factory(), // Tetap sediakan default-nya
        'status'   => fake()->randomElement(['siswa','lolos','cuti']),
        'foto'     => 'default.jpg',
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Core $core) {
            DetailSiswa::factory()->create([
                'nis' => $core->nis,
            ]);
        });
    }
}
