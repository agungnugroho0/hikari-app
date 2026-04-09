<?php

namespace App\Livewire\Pages;

use App\Models\Kelas;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.sensei')]
#[Title('Laporan Guru')]
class LaporanGuru extends BaseLaporan
{
    public string $nama = '';

    public ?string $foto = null;

    public ?Staff $sensei = null;

    protected function initializeContext(): void
    {
        $this->nama = (string) Auth::user()->nama_s;
        $this->foto = Auth::user()->foto_s;
        $this->sensei = Staff::with('kelas')->findOrFail(Auth::user()->id_staff);
        $this->lockClassSelection = true;
    }

    protected function eligibleClassQuery()
    {
        return Kelas::query()->where('id_pengajar', $this->sensei?->id_staff);
    }

    protected function resolveInitialClassId(): ?string
    {
        return $this->sensei?->kelas?->id_kelas ?? parent::resolveInitialClassId();
    }

    public function render()
    {
        return view('pages.laporan-guru');
    }
}
