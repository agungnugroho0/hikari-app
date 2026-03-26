<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Formulir Nilai {{ $monthName }} {{ $year }}</title>
</head>

<body>
    @php
        $scoreColumns = range(1, 30);
        $totalColumns = 4 + count($scoreColumns);
    @endphp
    <table border="1">
        <tr>
            <td colspan="{{ $totalColumns }}" style="font-weight: bold; font-size: 16px;">Formulir Nilai Bulanan</td>
        </tr>
        <tr>
            <td colspan="{{ $totalColumns }}">Periode: {{ $monthName }} {{ $year }}</td>
        </tr>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            @foreach ($scoreColumns as $scoreColumn)
                <th></th>
            @endforeach
        </tr>
        @foreach ($students as $student)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="mso-number-format:'\@';">{{ $student->nis }}</td>
                <td>{{ $student->detail?->nama_lengkap }}</td>
                <td>{{ $student->kelas?->nama_kelas }}</td>
                @foreach ($scoreColumns as $scoreColumn)
                    <td></td>
                @endforeach
            </tr>
        @endforeach
    </table>
</body>

</html>
