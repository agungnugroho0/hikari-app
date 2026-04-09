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
            line-height: 1.6;
            margin: 18px 22px;
        }

        .letterhead {
            width: 100%;
            border-bottom: 3px solid #111827;
            padding-bottom: 10px;
            margin-bottom: 22px;
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
            margin: 16px 0 14px;
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

        .meta-table,
        .identity-table,
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table {
            margin-bottom: 14px;
        }

        .meta-table td,
        .identity-table td,
        .signature-table td {
            vertical-align: top;
        }

        .identity-table {
            margin: 10px 0 16px;
        }

        .identity-table td {
            padding: 2px 0;
        }

        .body-copy {
            text-align: justify;
            margin: 0 0 12px;
        }

        .signature-table {
            margin-top: 28px;
        }

        .signature-date {
            text-align: right;
            padding-bottom: 8px;
        }

        .signature-label {
            text-align: right;
            padding-bottom: 64px;
        }

        .signature-name {
            text-align: right;
            font-weight: bold;
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

        $tanggalLahir = $siswa->detail?->tgl_lahir?->translatedFormat('d F Y') ?? '-';
        $tempatTanggalLahir = trim(($siswa->detail?->tempat_lhr ?? '-') . ', ' . $tanggalLahir, ', ');
        $penandatangan = $document['signatures'][0] ?? null;
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

    <p class="body-copy">Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

    <table class="identity-table">
        <tr>
            <td width="150">Nama Lengkap</td>
            <td>: {{ $siswa->detail?->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tempat/Tanggal Lahir</td>
            <td>: {{ $tempatTanggalLahir }}</td>
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
        Demikian surat rekomendasi ini dibuat untuk dipergunakan sebagaimana mestinya.
    </p>

    <table class="signature-table">
        <tr>
            <td class="signature-date">Kendal, {{ $issuedDate->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="signature-label">{{ $penandatangan['label'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="signature-name">{{ $penandatangan['name'] ?? '' }}</td>
        </tr>
    </table>
</body>
</html>
