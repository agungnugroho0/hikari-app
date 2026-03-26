<head>
    <style>
        .wrapper {
            position: relative;
            width: 400px;
        }

        .nafuda {
            width: 100%;
        }

        .qr {
            position: absolute;
            top: 40px;
            right: 40px;
            width: 60px;
        }
    </style>

</head>

<div class="wrapper">

    {{-- <img src="{{ public_path('img/nafuda.png') }}" class="nafuda"> --}}
    <img src="{{ $nafuda1 }}" class="nafuda">
    <img src="{{ $nafuda2 }}" class="nafuda">
    <img src="data:image/svg+xml;base64,{{ $qr }}" class="qr" alt="QR {{ $siswa->nis }}">

</div>
