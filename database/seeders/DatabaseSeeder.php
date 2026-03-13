<?php

namespace Database\Seeders;

use App\Models\Core;
use App\Models\Staff;
use App\Models\User;
use App\Models\Kelas;
use App\Models\ListLolos;
use App\Models\DetailSiswa;
use App\Models\ListWawancara;
use Database\Seeders\SoSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */

    
    public function run(): void
    {
        Staff::factory(10)->create();
        $this->call([
            SoSeeder::class,
        ]);
        Core::factory(50)
        ->recycle(Kelas::factory(5)->create())
        ->create();
        ListWawancara::factory(50)->create();

        Core::where('status', 'lolos')->each(function ($siswa) {
            ListLolos::factory()->create([
                'nis' => $siswa->nis,
            ]);
        });


        // Core::factory(50)->recycle(Kelas::factory(5)->create())->has(DetailSiswa::factory(),'detail')->create();
        
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
