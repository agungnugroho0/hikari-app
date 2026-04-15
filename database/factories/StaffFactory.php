<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'id_staff' => fake()->unique()->numberBetween(100,900),
            'nama_s' => fake()->name(),
            'no' => fake()->phoneNumber(),
            'akses' => fake()->randomElement(['admin','guru','dev']),
            'foto_s' => Str::random(10),
            'password' => Hash::make('123456'),
            'username' => fake()->userName(),
        ];
    }
}
