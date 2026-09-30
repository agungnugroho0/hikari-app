<?php

namespace App\Livewire\Pages;

use App\Models\Absen;
use App\Models\Core;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Absensi Siswa')]
class AdminAttendance extends Component
{
    private const STATUSES = [
        'hadir' => 'H',
        'izin' => 'I',
        'alfa' => 'A',
        'sakit' => 'S',
        'mensetsu' => 'M',
    ];

    public string $selectedNis = '';

    public string $attendanceDate = '';

    public string $message = '';

    public function mount(): void
    {
        $this->attendanceDate = now()->toDateString();
    }

    public function saveAttendance(string $status): void
    {
        abort_unless(array_key_exists($status, self::STATUSES), 404);

        $validated = $this->validate([
            'selectedNis' => ['required', 'exists:core,nis'],
            'attendanceDate' => ['required', 'date'],
        ], [
            'selectedNis.required' => 'Pilih siswa terlebih dahulu.',
            'attendanceDate.required' => 'Pilih tanggal absensi.',
        ]);

        Core::query()
            ->where('nis', $validated['selectedNis'])
            ->where('status', 'siswa')
            ->firstOrFail();

        $date = Carbon::parse($validated['attendanceDate'])->toDateString();
        DB::transaction(function () use ($validated, $date, $status) {
            $attendance = Absen::query()
                ->where('nis', $validated['selectedNis'])
                ->whereDate('tgl', $date)
                ->lockForUpdate()
                ->first();

            if ($attendance) {
                $attendance->update(['ket' => self::STATUSES[$status]]);

                return;
            }

            Absen::create([
                'id_absen' => $this->generateId($date),
                'nis' => $validated['selectedNis'],
                'tgl' => $date,
                'ket' => self::STATUSES[$status],
            ]);
        });

        $this->message = 'Absensi '.$status.' berhasil disimpan untuk '.$date.'.';
    }

    public function render()
    {
        $students = Core::query()
            ->with(['detail', 'kelas'])
            ->where('status', 'siswa')
            ->get()
            ->map(fn (Core $student) => [
                'nis' => $student->nis,
                'name' => $student->detail?->nama_lengkap ?? 'Nama belum tersedia',
                'class' => $student->kelas?->nama_kelas ?? 'Tanpa kelas',
            ]);

        $currentAttendance = $this->selectedNis && $this->attendanceDate
            ? Absen::query()
                ->where('nis', $this->selectedNis)
                ->whereDate('tgl', $this->attendanceDate)
                ->first()
            : null;

        return view('pages.admin-attendance', [
            'students' => $students,
            'currentAttendance' => $currentAttendance,
            'statusLabels' => array_flip(self::STATUSES),
        ]);
    }

    private function generateId(string $date): string
    {
        $prefix = 'ABS'.Carbon::parse($date)->format('Ymd');
        $latestId = Absen::query()
            ->where('id_absen', 'like', $prefix.'%')
            ->orderByDesc('id_absen')
            ->lockForUpdate()
            ->value('id_absen');
        $number = $latestId ? (int) substr($latestId, -3) + 1 : 1;

        return $prefix.str_pad((string) $number, 3, '0', STR_PAD_LEFT);
    }
}