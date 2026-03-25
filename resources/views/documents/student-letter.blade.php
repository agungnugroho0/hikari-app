<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $document['title'] }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            font-size: 12px;
            line-height: 1.5;
            margin: 12px 16px;
        }

        .letterhead {
            width: 100%;
            border-bottom: 3px solid #111827;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }

        .letterhead td {
            vertical-align: top;
        }

        .logo-cell {
            width: 76px;
        }

        .logo {
            width: 62px;
            height: 62px;
        }

        .agency-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .agency-address {
            margin: 3px 0 0;
            color: #111827;
            font-size: 10px;
            line-height: 1.35;
        }

        .document-title {
            text-align: center;
            margin: 14px 0 10px;
        }

        .document-title h1 {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .document-title p {
            margin: 3px 0 0;
            font-size: 11px;
        }

        .meta-table {
            margin-bottom: 10px;
        }

        .meta-table td {
            padding: 1px 0;
            vertical-align: top;
        }

        .recipient {
            margin: 12px 0;
        }

        .recipient p {
            margin: 0;
        }

        .student-data {
            width: 100%;
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0 14px;
        }

        .student-data td {
            padding: 2px 0;
            vertical-align: top;
        }

        .section-title {
            font-weight: bold;
            margin: 0 0 8px;
        }

        .body-copy {
            text-align: justify;
            margin: 0 0 10px;
        }

        .closing {
            margin-top: 12px;
        }

        .signature {
            width: 100%;
            margin-top: 20px;
        }

        .signature td {
            vertical-align: top;
            text-align: center;
            padding: 0 6px;
        }

        .signature.signature-2 td {
            width: 50%;
        }

        .signature.signature-3 td {
            width: 33.33%;
        }

        .signature.signature-4 td {
            width: 25%;
        }

        .signature-label {
            min-height: 30px;
        }

        .signature-space {
            height: 60px;
            position: relative;
        }

        .stamp {
            width: 52px;
            height: 52px;
            opacity: 0.2;
            position: absolute;
            top: 4px;
            left: 50%;
            transform: translateX(-50%);
        }

        .signature-logo {
            width: 50px;
            height: 50px;
            position: absolute;
            top: 5px;
            left: 50%;
            transform: translateX(-50%);
        }

        .small-note {
            margin: 0;
            font-size: 10px;
            color: #4b5563;
        }
    </style>
</head>
<body>
    @php
        $path = public_path('img/logo.jpg');
        $logo = null;

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    @endphp

    <table class="letterhead">
        <tr>
            <td class="logo-cell">
                @if ($logo)
                    <img src="{{ $logo }}" alt="Logo Hikari" class="logo">
                @endif
            </td>
            <td>
                <p class="agency-name">LPK Hikari Gakkou</p>
                <p class="agency-address">
                    Jl.Lingkar Sumberagung , Nawangsari, Kendal, Jawa Tengah<br>
                    Telp : 0822-6056-0520 email: lpkhikarig@gmail.com
                </p>
            </td>
        </tr>
    </table>

    <div class="document-title">
        <h1>{{ $document['title'] }}</h1>
        <p>Nomor: {{ $documentNumber }}</p>
    </div>

    <table class="meta-table">
        <tr>
            <td width="90">Lampiran</td>
            <td>: -</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>: {{ $document['subject'] }}</td>
        </tr>
    </table>

    <div class="recipient">
        <p>Kepada Yth.</p>
        <p>{{ $document['recipient'] ?? ($document['slug'] === 'pengajuan-cuti' ? 'Admin/Pengelola LPK Hikari Gakkou' : 'Orang Tua/Wali Siswa') }}</p>
        <p>di Tempat</p>
    </div>

    <p class="body-copy">Dengan hormat,</p>
    <p class="body-copy">
        Sehubungan dengan administrasi siswa di lingkungan LPK Hikari Gakkou, bersama ini kami menyampaikan surat terkait data siswa sebagai berikut:
    </p>

    <table class="student-data">
        <tr>
            <td width="120">Nama</td>
            <td>: {{ $siswa->detail?->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIS</td>
            <td>: {{ $siswa->nis }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>: {{ $siswa->kelas?->nama_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td>No. WhatsApp</td>
            <td>: {{ $siswa->detail?->wa ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: {{ $siswa->detail?->alamat ?? '-' }}</td>
        </tr>
    </table>

    <p class="body-copy">
        {{ $document['body'] }}
    </p>
    <p class="body-copy">
        Surat ini dibuat sebagai dokumen resmi administrasi lembaga dan dapat dipergunakan sebagaimana mestinya sesuai ketentuan yang berlaku.
    </p>
    <p class="body-copy closing">Atas perhatian dan kerja samanya, kami ucapkan terima kasih.</p>

    @php
        $signatures = collect($document['signatures'] ?? [])->map(function (array $signature) use ($siswa) {
            if ($signature['label'] === 'Wali Kelas') {
                $signature['name'] = $siswa->kelas?->pengajar?->nama_s ?? '_____________________';
            }

            if ($signature['label'] === 'Siswa') {
                $signature['name'] = $siswa->detail?->nama_lengkap ?? '_____________________';
            }

            if ($signature['label'] === 'Siswa/Penerima') {
                $signature['name'] = $siswa->detail?->nama_lengkap ?? '_____________________';
            }

            return $signature;
        })->values();
    @endphp

    <table class="signature signature-{{ $signatures->count() }}">
        <tr>
            @foreach ($signatures as $signature)
                <td>{{ $loop->first ? 'Kendal, ' . $issuedDate->translatedFormat('d F Y') : '' }}</td>
            @endforeach
        </tr>
        <tr>
            @foreach ($signatures as $signature)
                <td class="signature-label">{{ $signature['label'] }}</td>
            @endforeach
        </tr>
        <tr>
            @foreach ($signatures as $signature)
                <td class="signature-space">
                    @if (($signature['use_logo'] ?? false) && $logo)
                        <img src="{{ $logo }}" alt="Logo LPK" class="signature-logo">
                    @elseif (($document['stamp_on'] ?? null) === $signature['label'] && $logo)
                        <img src="{{ $logo }}" alt="Stempel LPK" class="stamp">
                    @endif
                </td>
            @endforeach
        </tr>
        <tr>
            @foreach ($signatures as $signature)
                <td><strong>{{ $signature['name'] }}</strong></td>
            @endforeach
        </tr>
    </table>
    @if (($document['stamp_on'] ?? null) !== null)
        <p class="small-note" style="margin-top: 8px; text-align: center;">Stempel LPK dibubuhkan pada kolom tanda tangan yang ditentukan.</p>
    @endif
</body>
</html>
