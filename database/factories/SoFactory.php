<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\So>
 */
class SoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_so' => 'SO'.now()->format('Ymd').fake()->unique()->numberBetween(000,999),
            'nama_so' => fake()->name(),
            'foto_so' => 'foto.jpg',
            'lokasi' => Str::random(10),
            'pj' => fake()->name()
        ];
    }
}
