<?php
namespace App\Livewire;

use App\Models\Core;
use App\Models\Staff;
use App\Services\presensiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class daftarHadir extends Component
{

    #[Computed]
    public function daftarSiswa()
    {
        $today = now()->toDateString();

         $staff = Staff::with('kelas')->findOrFail(Auth::user()->id_staff);

        return Core::where('id_kelas', $staff->kelas->id_kelas) // 🔥 ini fix
            ->where('status', 'siswa')->whereDoesntHave('absensi', function ($q) use ($today) {
                $q->whereDate('tgl', $today);
            })->get();
    }

    public function absen($id, $status,PresensiService $service)
    {

        $result = $service->absen($id, $status);

       if ($result) {
        unset($this->daftarSiswa);

        $this->dispatch('notif', 
            message: "Absensi berhasil : $status",
            type: 'success'
            );
        
        }
        
    }

    #[On('refresh')]
    public function refresh()
    {
        unset($this->daftarSiswa); // 🔥 refresh computed
    }

    public function render(){
        return view('daftar-hadir');
    }
};
