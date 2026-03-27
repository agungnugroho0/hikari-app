<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Staff;
use App\Models\Core;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\presensiService;

new class extends Component
{
    // public $service;

    // public function mount(PresensiService $service)
    // {
    //     $this->service = $service;
    // }
    #[Computed]
    public function daftarSiswa()
    {
        $today = now()->toDateString();

         $staff = Staff::with('kelas')->findOrFail(Auth::user()->id_staff);

        return Core::where('id_kelas', $staff->kelas->id_kelas) // 🔥 ini fix
            ->where('status', 'siswa')->whereDoesntHave('absensi', function ($q) use ($today) {
                $q->whereDate('tgl', $today);
            })
            ->get();
    }

    public function absen($id, $status,PresensiService $service)
    {

        $result = $service->absen($id, $status);

        if ($result) {
            unset($this->daftarSiswa); // refresh
        }
    }

};
?>

<div>
    
    @foreach($this->daftarSiswa as $s)
        <div class="md:flex items-center justify-between p-3 border-b">
            <div class="">
                {{ $s->detail->nama_lengkap }}
            </div>
            <div class="flex gap-1 mt-2 md:mt-0">

                <button wire:click="absen({{ $s->nis }}, 'h')"
                    class="px-2 py-1 bg-green-800 text-white rounded">
                    Hadir
                </button>

                <button wire:click="absen({{ $s->nis }}, 'm')"
                    class="px-2 py-1 bg-blue-500 text-white rounded">
                    Mensetsu
                </button>

                <button wire:click="absen({{ $s->nis }}, 'i')"
                    class="px-2 py-1 bg-yellow-500 text-white rounded">
                    Ijin
                </button>

                <button wire:click="absen({{ $s->nis }}, 'a')"
                    class="px-2 py-1 bg-red-500 text-white rounded">
                    Alfa
                </button>

            </div>
        </div>
    @endforeach
</div>