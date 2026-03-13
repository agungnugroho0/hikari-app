<?php

namespace Database\Seeders;

use App\Models\JobFairModels;
use App\Models\So;
use Illuminate\Database\Seeder;

class SoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        So::factory(10)->create();
        JobFairModels::factory(20)->create();
        
    }

    
}
