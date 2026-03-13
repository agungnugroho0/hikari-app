<?php

namespace Database\Factories;

use App\Models\So;

use App\Models\Core;
use Illuminate\Support\Str;
use function Symfony\Component\Clock\now;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ListLolos>
 */
class ListLolosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_lolos' => 'L'.now()->format('Ymd').fake()->unique()->randomNumber(),
            'nis' => Core::where('status','lolos')->value('nis'),
            'tgl_lolos' => now()->format('Ymd'),
            'id_so' => So::inRandomOrder()->value('id_so'),
            'nama_job' => Str::random(),
            'nama_perusahaan' => fake()->company()
        ];
    }
}
