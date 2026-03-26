<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Settings>
 */
class SettingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_st' => 'ST'.now()->format('Ymd').fake()->unique()->numberBetween(000,999),
            'nama_set' => fake()->name(),
            'ket'=> Str::random(20)
        ];
    }
}
