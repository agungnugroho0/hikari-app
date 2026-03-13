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
            width: 80px;
        }
    </style>

</head>

<div class="wrapper">

    <img src="{{ public_path('img/nafuda.png') }}" class="nafuda">

    <img src="data:image/png;base64,{{ $qr }}" class="qr">

</div>
