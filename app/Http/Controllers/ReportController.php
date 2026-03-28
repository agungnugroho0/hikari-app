<?php

namespace App\Http\Controllers;

use App\Models\Core;
use App\Models\Kelas;
use Illuminate\Support\Carbon;
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

        $students = Core::query()
            ->with(['detail', 'kelas'])
            ->whereDoesntHave('listlolos')
            ->orderBy('id_kelas')
            ->orderBy('nis')
            ->get();

        $filename = sprintf(
            'formulir-nilai-%d-%02d.xls',
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

    // public function monthlyAttendanceSheet(Request $request)
    // {
    //     $validated = $request->validate([
    //         'year' => ['required', 'integer', 'between:2000,2100'],
    //         'month' => ['required', 'integer', 'between:1,12'],
    //         'class_id' => ['required', 'integer', 'exists:kelas,id_kelas'],
    //     ]);

    //     $year = (int) $validated['year'];
    //     $month = (int) $validated['month'];
    //     $classId = (int) $validated['class_id'];
    //     $startDate = Carbon::create($year, $month, 1)->startOfMonth();
    //     $endDate = $startDate->copy()->endOfMonth();

    //     $kelas = Kelas::query()->findOrFail($classId);

    //     $attendanceSubquery = DB::table('absen')
    //         ->selectRaw("
    //             nis,
    //             SUM(CASE WHEN LOWER(ket) = 'hadir' THEN 1 ELSE 0 END) as hadir,
    //             SUM(CASE WHEN LOWER(ket) = 'mensetsu' THEN 1 ELSE 0 END) as mensetsu,
    //             SUM(CASE WHEN LOWER(ket) = 'ijin' THEN 1 ELSE 0 END) as ijin,
    //             SUM(CASE WHEN LOWER(ket) IN ('alfa', 'alpha') THEN 1 ELSE 0 END) as alfa
    //         ")
    //         ->whereBetween('tgl', [$startDate->toDateString(), $endDate->toDateString()])
    //         ->groupBy('nis');

    //     $students = DB::table('core')
    //         ->join('detail_siswa', 'detail_siswa.nis', '=', 'core.nis')
    //         ->join('kelas', 'kelas.id_kelas', '=', 'core.id_kelas')
    //         ->leftJoinSub($attendanceSubquery, 'attendance', fn ($join) => $join->on('attendance.nis', '=', 'core.nis'))
    //         ->where('core.id_kelas', $classId)
    //         ->orderBy('detail_siswa.nama_lengkap')
    //         ->get([
    //             'core.nis',
    //             'detail_siswa.nama_lengkap',
    //             'kelas.nama_kelas',
    //             DB::raw('COALESCE(attendance.hadir, 0) as hadir'),
    //             DB::raw('COALESCE(attendance.mensetsu, 0) as mensetsu'),
    //             DB::raw('COALESCE(attendance.ijin, 0) as ijin'),
    //             DB::raw('COALESCE(attendance.alfa, 0) as alfa'),
    //         ])
    //         ->map(function ($student) {
    //             $student->total = (int) $student->hadir + (int) $student->mensetsu + (int) $student->ijin + (int) $student->alfa;

    //             return $student;
    //         });

    //     $recap = collect(self::ATTENDANCE_STATUSES)
    //         ->map(function (string $label, string $key) use ($students) {
    //             return [
    //                 'key' => $key,
    //                 'label' => $label,
    //                 'total' => (int) $students->sum($key),
    //             ];
    //         })
    //         ->values();

    //     $dailyRows = DB::table('absen')
    //         ->join('core', 'core.nis', '=', 'absen.nis')
    //         ->selectRaw("
    //             DAY(absen.tgl) as day_number,
    //             CASE
    //                 WHEN LOWER(absen.ket) = 'alpha' THEN 'alfa'
    //                 ELSE LOWER(absen.ket)
    //             END as status_key,
    //             COUNT(*) as total
    //         ")
    //         ->whereBetween('absen.tgl', [$startDate->toDateString(), $endDate->toDateString()])
    //         ->where('core.id_kelas', $classId)
    //         ->groupByRaw("
    //             DAY(absen.tgl),
    //             CASE
    //                 WHEN LOWER(absen.ket) = 'alpha' THEN 'alfa'
    //                 ELSE LOWER(absen.ket)
    //             END
    //         ")
    //         ->orderByRaw('DAY(absen.tgl)')
    //         ->get()
    //         ->groupBy('day_number')
    //         ->map(fn ($items) => $items->pluck('total', 'status_key'));

    //     $dailySeries = collect(range(1, $startDate->daysInMonth))
    //         ->map(function (int $dayNumber) use ($dailyRows, $startDate) {
    //             $row = $dailyRows->get($dayNumber, collect());

    //             return [
    //                 'label' => $startDate->copy()->day($dayNumber)->format('d/m'),
    //                 'hadir' => (int) ($row['hadir'] ?? 0),
    //                 'mensetsu' => (int) ($row['mensetsu'] ?? 0),
    //                 'ijin' => (int) ($row['ijin'] ?? 0),
    //                 'alfa' => (int) ($row['alfa'] ?? 0),
    //             ];
    //         })
    //         ->all();

    //     $maxRecapTotal = max(1, (int) $recap->max('total'));
    //     $filename = sprintf('laporan-absensi-%s-%d-%02d.xls', str($kelas->nama_kelas)->slug(), $year, $month);

    //     return response()
    //         ->view('exports.attendance-report', [
    //             'students' => $students,
    //             'recap' => $recap,
    //             'dailySeries' => $dailySeries,
    //             'year' => $year,
    //             'month' => $month,
    //             'monthName' => $startDate->translatedFormat('F'),
    //             'className' => $kelas->nama_kelas,
    //             'maxRecapTotal' => $maxRecapTotal,
    //             'daysInMonth' => $startDate->daysInMonth,
    //         ])
    //         ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
    //         ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    // }
}
