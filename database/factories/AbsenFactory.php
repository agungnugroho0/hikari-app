<?php

namespace Database\Factories;

use App\Models\Absen;
use App\Models\Core;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absen>
 */
class AbsenFactory extends Factory
{
    protected $model = Absen::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_absen' => 'ABS-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
            'nis' => Core::query()->inRandomOrder()->value('nis') ?? Core::factory()->create()->nis,
            'tgl' => fake()->date(),
            'ket' => fake()->randomElement(['h', 'i', 's', 'a']),
        ];
    }
}
