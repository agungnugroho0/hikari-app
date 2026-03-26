<?php

namespace Database\Seeders;

use App\Models\Absen;
use App\Models\Core;
use App\Models\DetailSiswa;
use App\Models\Kelas;
use App\Models\ListLolos;
use App\Models\ListWawancara;
use App\Models\Staff;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Models\User;
use Database\Seeders\SettingsSeeder;
use Database\Seeders\SoSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */

    
    public function run(): void
    {
        Staff::factory(10)->create();
        $staff = Staff::all();

        Kelas::factory(10)->make()->each(function ($kelas) use ($staff) {
        $kelas->id_pengajar = $staff->random()->id_staff;
        $kelas->save();});
        $this->call([
            SoSeeder::class,
            SettingsSeeder::class,
        ]);
        
        $kelas = Kelas::all();

        Core::factory(50)
    ->state(function () use ($kelas) {
        return [
            'id_kelas' => $kelas->random()->id_kelas,
        ];
    })
    ->create();
        Absen::factory(50)->create();
        ListWawancara::factory(50)->create();

        Core::where('status', 'lolos')->each(function ($siswa) {
            ListLolos::factory()->create([
                'nis' => $siswa->nis,
            ]);
        });

        Tagihan::factory(50)->create();

        $tagihan = Tagihan::with('tagihansiswa.detail')->get();

        $tagihan->each(function ($t) {

            $sisa = $t->kekurangan_tagihan;

            while ($sisa > 0) {

                $bayar = rand(100000, min(500000, $sisa));

                Transaksi::create([
                    'id_tx' => 'TX-' . uniqid(),
                    'nis' => $t->nis,
                    'id_t' => $t->id_t,

                    // 🔥 ambil dari detail_siswa
                    'nama_lengkap' => $t->tagihansiswa->detail->nama_lengkap ?? 'Siswa',

                    'nama_transaksi' => $t->nama_tagihan,
                    'tgl_transaksi' => now(),
                    'nominal' => $bayar,
                ]);

                $sisa -= $bayar;
            }
        });
        // Core::factory(50)->recycle(Kelas::factory(5)->create())->has(DetailSiswa::factory(),'detail')->create();
        
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
