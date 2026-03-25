<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Billing Statement</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.3;
            margin: 10px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 2px 0;
        }

        .summary-wrap {
            margin: 10px 0;
            padding: 6px 8px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .summary-table td {
            padding: 2px 0;
        }

        .text-right {
            text-align: right;
        }

        .section-title {
            margin: 10px 0 5px;
            font-size: 13px;
            font-weight: bold;
        }

        .tagihan-card {
            margin-bottom: 10px;
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
        }

        .tagihan-header div {
            margin-bottom: 2px;
        }

        .tagihan-name {
            font-weight: bold;
            font-size: 12px;
        }

        .status {
            padding: 1px 6px;
            border: 1px solid #9ca3af;
            font-size: 10px;
        }

        .transaction-table th,
        .transaction-table td {
            border: 1px solid #e5e7eb;
            padding: 4px 6px;
            font-size: 10px;
        }

        .transaction-table th {
            background: #f3f4f6;
        }

        .empty {
            font-size: 10px;
            color: #6b7280;
            font-style: italic;
        }

        .header {
    position: relative;
    margin-bottom: 10px;
    display: flex;
    }

    .logo-bg {
        position: relative;
        top: 14px;
        /* right: 0; */
        width: 40px;
        /* opacity: 0.08; biar samar */
    }

    .title {
        font-size: 18px;
        font-weight: bold;
        position: relative;
        z-index: 2;
    }

    .subtitle {
        font-size: 10px;
        color: #6b7280;
        position: relative;
        z-index: 2;
    }
    </style>
</head>
<body>

    <div class="header">
    <!-- Logo Background -->
    @php
    $path = public_path('img/logo.jpg');
    if (file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    @endphp

    @if(isset($logo))
        <img src="{{ $logo }}" class="logo-bg" ><span class="title">Billing Statement LPK Hikari Gakkou</span></img>
    @endif

    <!-- Text -->
    
    <div class="subtitle">
        <p>No. {{$nodoc}}<br>
            Tanggal: {{ now()->format('Y-m-d') }}</>
    </div>
</div>

    <table class="meta-table">
        <tr><td width="100">NIS</td><td>: {{ $siswa->nis }}</td></tr>
        <tr><td>Nama</td><td>: {{ optional($siswa->detail)->nama_lengkap }}</td></tr>
        <tr><td>Kelas</td><td>: {{ optional($siswa->kelas)->nama_kelas }}</td></tr>
        <tr><td>No. Telp</td><td>: {{ optional($siswa->detail)->wa }}</td></tr>
    </table>

    <div class="summary-wrap">
        <table class="summary-table">
            <tr>
                <td>Total</td>
                <td class="text-right">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Terbayar</td>
                <td class="text-right">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Sisa</td>
                <td class="text-right">Rp {{ number_format($totalSisa, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Tagihan</div>

    @forelse ($tagihanList as $tagihan)
        <div class="tagihan-card">
            <div class="tagihan-header">
                <div class="tagihan-name">{{ $tagihan->nama_tagihan }}</div>
                <div>{{$tagihan->id_t}} | Tanggal terbit tagihan : {{ $tagihan->tgl_terbit }} | 
                    <span class="status">{{ $tagihan->status_tagihan }}</span>
                </div>
                <div>
                    Total: Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }} |
                    Bayar: Rp {{ number_format($tagihan->total_tagihan - $tagihan->kekurangan_tagihan, 0, ',', '.') }} |
                    Sisa: Rp {{ number_format($tagihan->kekurangan_tagihan, 0, ',', '.') }}
                </div>
            </div>

            <table class="transaction-table">
                <thead>
                    <tr>
                        <th width="30">No</th>
                        <th width="90">Tanggal</th>
                        <th>Transaksi</th>
                        <th width="100" class="text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tagihan->listtx as $i => $trx)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $trx->tgl_transaksi }}</td>
                            <td>{{ $trx->nama_transaksi }}</td>
                            <td class="text-right">Rp {{ number_format($trx->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @empty
        <p class="empty">Belum ada tagihan</p>
    @endforelse

</body>
</html>