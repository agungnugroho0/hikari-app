<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi {{ $className }} {{ $monthName }} {{ $year }}</title>
</head>

<body>
    @php
        $chartScale = 30;
        $statusColors = [
            'hadir' => '#16a34a',
            'mensetsu' => '#2563eb',
            'ijin' => '#d97706',
            'alfa' => '#dc2626',
        ];
        $recapTotals = $recap->pluck('total', 'key');
    @endphp

    <table border="1">
        <tr>
            <td colspan="7" style="font-weight: bold; font-size: 16px;">Laporan Absensi Bulanan</td>
        </tr>
        <tr>
            <td colspan="7">Kelas: {{ $className }}</td>
        </tr>
        <tr>
            <td colspan="7">Periode: {{ $monthName }} {{ $year }}</td>
        </tr>
    </table>

    <br>

    <table border="1">
        <tr>
            <th colspan="4" style="background-color: #e5e7eb;">Rekapan Absensi</th>
        </tr>
        <tr>
            @foreach ($recap as $item)
                <th>{{ $item['label'] }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach ($recap as $item)
                <td>{{ $item['total'] }}</td>
            @endforeach
        </tr>
    </table>

    <br>

    <table border="1">
        <tr>
            <th colspan="3" style="background-color: #e5e7eb;">Grafik Rekap Absensi</th>
        </tr>
        <tr>
            <th>Status</th>
            <th>Total</th>
            <th>Grafik</th>
        </tr>
        @foreach ($recap as $item)
            @php
                $barLength = $item['total'] > 0 ? max(1, (int) round(($item['total'] / $maxRecapTotal) * $chartScale)) : 0;
            @endphp
            <tr>
                <td>{{ $item['label'] }}</td>
                <td>{{ $item['total'] }}</td>
                <td style="color: {{ $statusColors[$item['key']] }};">{{ str_repeat('|', $barLength) }}</td>
            </tr>
        @endforeach
    </table>

    <br>

    <table border="1">
        <tr>
            <th colspan="7" style="background-color: #e5e7eb;">Detail Absensi per Siswa</th>
        </tr>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Hadir</th>
            <th>Mensetsu</th>
            <th>Ijin</th>
            <th>Alfa</th>
        </tr>
        @foreach ($students as $student)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="mso-number-format:'\@';">{{ $student->nis }}</td>
                <td>{{ $student->nama_lengkap }}</td>
                <td>{{ $student->hadir }}</td>
                <td>{{ $student->mensetsu }}</td>
                <td>{{ $student->ijin }}</td>
                <td>{{ $student->alfa }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3">Total</th>
            <th>{{ $recapTotals['hadir'] ?? 0 }}</th>
            <th>{{ $recapTotals['mensetsu'] ?? 0 }}</th>
            <th>{{ $recapTotals['ijin'] ?? 0 }}</th>
            <th>{{ $recapTotals['alfa'] ?? 0 }}</th>
        </tr>
    </table>

    <br>

    <table border="1">
        <tr>
            <th colspan="5" style="background-color: #e5e7eb;">Grafik Harian Absensi</th>
        </tr>
        <tr>
            <th>Tanggal</th>
            <th>Hadir</th>
            <th>Mensetsu</th>
            <th>Ijin</th>
            <th>Alfa</th>
        </tr>
        @foreach ($dailySeries as $day)
            <tr>
                <td>{{ $day['label'] }}</td>
                <td>{{ $day['hadir'] }}</td>
                <td>{{ $day['mensetsu'] }}</td>
                <td>{{ $day['ijin'] }}</td>
                <td>{{ $day['alfa'] }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
