<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Presensi {{ $className }} {{ $year }}-{{ str_pad((string) $month, 2, '0', STR_PAD_LEFT) }}</title>
</head>

<body>
    @php
        $dayColumnCount = count($days);
        $summaryColumns = 4;
        $totalColumns = 2 + $dayColumnCount + $summaryColumns;
    @endphp

    <table border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td colspan="{{ $totalColumns }}" style="font-weight: bold; font-size: 16px; text-align: center;">
                REKAP ABSENSI SISWA
            </td>
        </tr>
        <tr>
            <td colspan="{{ $totalColumns }}">Kelas: {{ $className }}</td>
        </tr>
        <tr>
            <td colspan="{{ $totalColumns }}">Periode: {{ $monthName }} {{ $year }}</td>
        </tr>
    </table>

    <br>

    <table border="1" cellspacing="0" cellpadding="4">
        <thead>
            <tr style="background-color: #dbeafe; font-weight: bold; text-align: center;">
                <th rowspan="2">NO</th>
                <th rowspan="2">NAMA</th>
                <th colspan="{{ $dayColumnCount }}">TANGGAL</th>
                <th colspan="{{ $summaryColumns }}">JUMLAH</th>
            </tr>
            <tr style="background-color: #eff6ff; text-align: center;">
                @foreach ($days as $day)
                    <th>{{ $day }}</th>
                @endforeach
                <th>HADIR</th>
                <th>MENSETSU</th>
                <th>IZIN</th>
                <th>ALPHA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $student->nama_lengkap }}</td>
                    @foreach ($days as $day)
                        <td style="text-align: center;">{{ $student->daily_statuses[$day] ?? '' }}</td>
                    @endforeach
                    <td style="text-align: center;">{{ $student->hadir }}</td>
                    <td style="text-align: center;">{{ $student->mensetsu }}</td>
                    <td style="text-align: center;">{{ $student->ijin }}</td>
                    <td style="text-align: center;">{{ $student->alfa }}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold; background-color: #f8fafc;">
                <td colspan="{{ 2 + $dayColumnCount }}" style="text-align: right;">TOTAL</td>
                <td style="text-align: center;">{{ $recap->firstWhere('key', 'hadir')['total'] ?? 0 }}</td>
                <td style="text-align: center;">{{ $recap->firstWhere('key', 'mensetsu')['total'] ?? 0 }}</td>
                <td style="text-align: center;">{{ $recap->firstWhere('key', 'ijin')['total'] ?? 0 }}</td>
                <td style="text-align: center;">{{ $recap->firstWhere('key', 'alfa')['total'] ?? 0 }}</td>
            </tr>
        </tbody>
    </table>

    <br>

    <table border="1" cellspacing="0" cellpadding="4">
        <tr style="background-color: #f1f5f9; font-weight: bold;">
            <th>Keterangan Kode</th>
            <th>Arti</th>
        </tr>
        <tr>
            <td>H</td>
            <td>Hadir</td>
        </tr>
        <tr>
            <td>M</td>
            <td>Mensetsu</td>
        </tr>
        <tr>
            <td>I</td>
            <td>Izin</td>
        </tr>
        <tr>
            <td>A</td>
            <td>Alpha</td>
        </tr>
    </table>
</body>

</html>
