<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\presensiService;


new class extends Component
{
    public $hasil = null;

    #[On('qr-scanned')]
    public function handleScan($value)
    {
        $this->hasil = $value;

        // contoh: langsung absen
        $result = app(PresensiService::class)->absen($this->hasil, 'h');
        $this->dispatch('refresh')->to('daftar-hadir');

        if ($result) {
            $this->dispatch('notif', 
                message: "Absensi berhasil",
                type: 'success'
            );
        } else {
            $this->dispatch('notif', 
                message: "Sudah absen hari ini!",
                type: 'warning'
            );
        }
        
    }
};
?>
<div>
    <div class="flex justify-center mb-3">
         <div class="flex gap-2">
            <button id="start-btn" 
                class="px-4 py-2 bg-green-600 text-white rounded">
                Mulai Scan
            </button>
    
            <button id="stop-btn" 
                class="px-4 py-2 bg-red-600 text-white rounded">
                Stop Scan
            </button>
        </div>
    </div>
    <div class="flex justify-center mb-3">
        <div id="reader" class="w-80 "></div>
    </div>
</div>