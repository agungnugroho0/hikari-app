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
    <div class="mb-4 flex justify-center">
         <div class="flex flex-wrap justify-center gap-2">
            <button id="start-btn"
                class="inline-flex min-h-11 items-center justify-center rounded-2xl bg-emerald-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600">
                Mulai Scan
            </button>

            <button id="stop-btn"
                class="inline-flex min-h-11 items-center justify-center rounded-2xl bg-red-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-800">
                Stop Scan
            </button>
        </div>
    </div>

    <div class="flex justify-center">
        <div id="reader" class="w-full max-w-sm overflow-hidden rounded-[28px] border border-neutral-200 bg-white p-3 shadow-sm"></div>
    </div>
</div>
