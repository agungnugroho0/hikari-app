<?php

namespace App\Http\Controllers;

use App\Models\Core;
use App\Models\Kelas;
use App\Models\Staff;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected const ATTENDANCE_STATUSES = [
        'hadir' => 'Hadir',
        'mensetsu' => 'Mensetsu',
        'ijin' => 'Ijin',
        'alfa' => 'Alfa',
    ];

    public function monthlyScoreSheet(Request $request)
    {
        $validated = $request->validate([
            'year' => ['required', 'integer', 'between:2000,2100'],
            'month' => ['required', 'integer', 'between:1,12'],
        ]);

        $year = (int) $validated['year'];
        $month = (int) $validated['month'];
        $classId = $this->resolveAuthorizedClassId($request);
        $kelas = $classId ? Kelas::query()->findOrFail($classId) : null;

        $students = Core::query()
            ->with(['detail', 'kelas'])
            ->whereDoesntHave('listlolos')
            ->when($classId, fn ($query) => $query->where('id_kelas', $classId))
            ->orderBy('id_kelas')
            ->orderBy('nis')
            ->get();

        $filename = sprintf(
            'formulir-nilai%s-%d-%02d.xls',
            $kelas ? '-'.str($kelas->nama_kelas)->slug() : '',
            $year,
            $month
        );

        return response()
            ->view('exports.monthly-score-sheet', [
                'students' => $students,
                'year' => $year,
                'month' => $month,
                'monthName' => now()->startOfYear()->month($month)->translatedFormat('F'),
            ])
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    public function monthlyAttendanceSheet(Request $request)
    {
        $validated = $request->validate([
            'year' => ['required', 'integer', 'between:2000,2100'],
            'month' => ['required', 'integer', 'between:1,12'],
            'class_id' => ['required', 'string', 'exists:kelas,id_kelas'],
        ]);

        $year = (int) $validated['year'];
        $month = (int) $validated['month'];
        $classId = $this->resolveAuthorizedClassId($request, $validated['class_id']);
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $days = range(1, $startDate->daysInMonth);

        $kelas = Kelas::query()->findOrFail($classId);

        $attendanceMap = DB::table('absen')
            ->join('core', 'core.nis', '=', 'absen.nis')
            ->selectRaw("
                absen.nis,
                DAY(absen.tgl) as day_number,
                CASE
                    WHEN LOWER(absen.ket) = 'h' THEN 'hadir'
                    WHEN LOWER(absen.ket) = 'm' THEN 'mensetsu'
                    WHEN LOWER(absen.ket) = 'i' THEN 'ijin'
                    WHEN LOWER(absen.ket) = 'a' THEN 'alfa'
                    ELSE null
                END as status_key
            ")
            ->whereBetween('absen.tgl', [$startDate->toDateString(), $endDate->toDateString()])
            ->where('core.id_kelas', $classId)
            ->get()
            ->filter(fn ($row) => filled($row->status_key))
            ->groupBy('nis')
            ->map(fn ($items) => $items->pluck('status_key', 'day_number'));

        $statusLetters = [
            'hadir' => 'H',
            'mensetsu' => 'M',
            'ijin' => 'I',
            'alfa' => 'A',
        ];

        $students = DB::table('core')
            ->join('detail_siswa', 'detail_siswa.nis', '=', 'core.nis')
            ->where('core.id_kelas', $classId)
            ->where('core.status', 'siswa')
            ->orderBy('detail_siswa.nama_lengkap')
            ->get([
                'core.nis',
                'detail_siswa.nama_lengkap',
            ])
            ->map(function ($student) use ($attendanceMap, $statusLetters, $days) {
                $attendance = collect($attendanceMap->get($student->nis, collect()));

                $dailyStatuses = collect($days)
                    ->mapWithKeys(fn (int $day) => [
                        $day => $statusLetters[$attendance->get($day)] ?? '',
                    ]);

                $student->daily_statuses = $dailyStatuses;
                $student->hadir = $dailyStatuses->filter(fn ($value) => $value === 'H')->count();
                $student->mensetsu = $dailyStatuses->filter(fn ($value) => $value === 'M')->count();
                $student->ijin = $dailyStatuses->filter(fn ($value) => $value === 'I')->count();
                $student->alfa = $dailyStatuses->filter(fn ($value) => $value === 'A')->count();

                return $student;
            });

        $recap = collect(self::ATTENDANCE_STATUSES)
            ->map(function (string $label, string $key) use ($students) {
                return [
                    'key' => $key,
                    'label' => $label,
                    'total' => (int) $students->sum($key),
                ];
            })
            ->values();

        $filename = sprintf('laporan-absensi-%s-%d-%02d.xls', str($kelas->nama_kelas)->slug(), $year, $month);

        return response()
            ->view('exports.attendance-report', [
                'students' => $students,
                'recap' => $recap,
                'days' => $days,
                'year' => $year,
                'month' => $month,
                'monthName' => $startDate->translatedFormat('F'),
                'className' => $kelas->nama_kelas,
            ])
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    protected function resolveAuthorizedClassId(Request $request, ?string $requestedClassId = null): ?string
    {
        $user = Auth::user();

        if (! $user || $user->akses !== 'guru') {
            return $requestedClassId;
        }

        $staff = Staff::with('kelas')->findOrFail($user->id_staff);
        $authorizedClassId = $staff->kelas?->id_kelas;

        abort_unless($authorizedClassId, 403);

        if ($requestedClassId !== null) {
            abort_unless($requestedClassId === $authorizedClassId, 403);
        }

        return $authorizedClassId;
    }
}
