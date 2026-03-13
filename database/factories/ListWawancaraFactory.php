<?php

namespace Database\Factories;

use App\Models\Core;
use App\Models\JobFairModels;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ListWawancara>
 */
class ListWawancaraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_list' => 'W'.now()->format('Ymd').fake()->unique()->numberBetween(000,999),
            'nis' => Core::inRandomOrder()->value('nis'),
            'id_job' => JobFairModels::inRandomOrder()->value('id_job'),
        ];
    }
}
