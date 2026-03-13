<?php

namespace Database\Factories;

use App\Models\So;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobFairModels>
 */
class JobFairModelsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_job'=> 'J'.now()->format('Ymd').fake()->unique()->numberBetween(000,999),
            'nama_job' => Str::random(30),
            'perusahaan' => fake()->company(),
            'tgl_wawancara' => now()->format('Y-m-d'),
            'penempatan' => Str::random(30),
            'metode' => Str::random(30),
            'id_so' => So::inRandomOrder()->value('id_so'),

        ];
    }
}
