<?php

namespace App\Livewire\Pages;

use App\Models\Core;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Dokumen')]
class Dokumen extends Component
{
    public ?string $selectedNis = null;
    public string $customDocumentName = '';

    public function mount(): void
    {
        $this->selectedNis = $this->studentOptions->first()['nis'] ?? null;
    }

    #[Computed]
    public function studentOptions(): Collection
    {
        return Core::query()
            ->with(['detail', 'kelas'])
            ->orderBy('nis')
            ->get()
            ->map(fn (Core $siswa) => [
                'nis' => $siswa->nis,
                'name' => $siswa->detail?->nama_lengkap ?? $siswa->nis,
                'class' => $siswa->kelas?->nama_kelas ?? '-',
            ]);
    }

    public function render()
    {
        return view('pages.dokumen');
    }
}
