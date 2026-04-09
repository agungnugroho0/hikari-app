<?php
namespace App\Livewire\Pages;

use App\Models\Core;
use App\Models\Kelas;
use App\Models\Settings;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.sensei')]
#[Title('Data Siswa Guru')]
class daftarSiswaGuru extends Component
{
    public $siswa;

    public string $nama = '';

    public ?string $foto = null;

    public ?Staff $sensei = null;

    public bool $canPromoteStudents = false;

    public ?string $nextClassId = null;

    public function mount(): void
    {
        $user = Auth::user();

        $this->nama = (string) $user->nama_s;
        $this->foto = $user->foto_s;
        $this->refreshSensei();
        $this->resolvePromotionState();
    }

    protected function refreshSensei(): void
    {
        $this->sensei = Staff::with([
            'kelas.core' => fn ($query) => $query->where('status', 'siswa')->with('detail'),
        ])->findOrFail(Auth::user()->id_staff);

        $this->siswa = $this->sensei;
    }

    protected function resolvePromotionState(): void
    {
        $this->nextClassId = $this->resolveNextClassId();
        $this->canPromoteStudents = filled($this->nextClassId);
    }

    protected function resolveNextClassId(): ?string
    {
        $ukkDate = $this->resolveUkkDate();
        $currentLevel = (int) ($this->sensei?->kelas?->tingkat ?? 0);

        if ($ukkDate !== today()->toDateString() || $currentLevel <= 1) {
            return null;
        }

        return Kelas::query()
            ->where('tingkat', $currentLevel - 1)
            ->orderBy('nama_kelas')
            ->value('id_kelas');
    }

    protected function resolveUkkDate(): ?string
    {
        $rawDate = Settings::query()
            ->where('nama_set', 'ukk')
            ->value('ket');

        if (blank($rawDate)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('d/m/Y', (string) $rawDate)->toDateString();
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function naikKelas(string $nis): void
    {
        $currentClassId = $this->sensei?->kelas?->id_kelas;

        if (! $this->nextClassId || ! $currentClassId) {
            $this->dispatch('kirim', message: 'Kenaikan kelas belum tersedia hari ini.', type: 'error');

            return;
        }

        $student = Core::query()
            ->where('nis', $nis)
            ->where('status', 'siswa')
            ->where('id_kelas', $currentClassId)
            ->first();

        if (! $student) {
            $this->dispatch('kirim', message: 'Siswa tidak ditemukan di kelas Anda.', type: 'error');

            return;
        }

        if ($student->id_kelas === $this->nextClassId) {
            $this->dispatch('kirim', message: 'Siswa sudah berada di kelas tujuan.', type: 'warning');

            return;
        }

        $student->update([
            'id_kelas' => $this->nextClassId,
        ]);

        $this->refreshSensei();
        $this->resolvePromotionState();

        $this->dispatch('kirim', message: 'Siswa berhasil dinaikkan kelas.');
    }

    public function render()
    {
        return view('pages.daftar-siswa-guru');
    }
};
