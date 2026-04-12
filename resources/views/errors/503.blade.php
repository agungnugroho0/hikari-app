<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maintenance</title>
    <style>
        :root {
            --bg: #f2f2f2;
            --text: #2f3338;
            --muted: #6f7680;
            --blue-a: #8ddcf9;
            --blue-b: #13a7ef;
            --blue-c: #0077cc;
            --green-a: #2df56a;
            --green-b: #08cd4f;
            --green-c: #00a03d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .maintenance-wrap {
            width: min(920px, 96vw);
            text-align: center;
            padding: 2.5rem 1rem 1.25rem;
        }

        .brand {
            margin: 0 auto 1.1rem;
            display: inline-flex;
            justify-content: center;
        }

        .brand img {
            width: clamp(72px, 10vw, 110px);
            height: auto;
            display: block;
        }

        h1 {
            margin: 0;
            font-size: clamp(1.9rem, 3.4vw, 3rem);
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: 0.01em;
        }

        p {
            margin: 1rem 0 0;
            font-size: clamp(0.9rem, 1.5vw, 1.1rem);
            color: var(--muted);
            line-height: 1.5;
            font-weight: 600;
        }

        .art {
            margin-top: 2.25rem;
        }

        .art svg {
            width: min(860px, 100%);
            height: auto;
            display: block;
            margin: 0 auto;
        }

        @media (max-width: 640px) {
            .maintenance-wrap {
                padding-top: 1.75rem;
            }

            .art {
                margin-top: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <main class="maintenance-wrap">
        <div class="brand">
            <img src="{{ asset('img/logo.png') }}" alt="Logo">
        </div>
        <h1>Website / Server lagi dibenerin</h1>
        <p>
            Sabar ya....<br>
            本当にごめんね。
        </p>

        <div class="art" aria-hidden="true">
            <svg viewBox="0 0 920 250" role="presentation">
                <path d="M0 125 H155" stroke="var(--blue-a)" stroke-width="14" fill="none" stroke-linecap="round"/>
                <path d="M155 125 c0 0 10 0 10 10 v18 c0 10 10 10 10 10 h35" stroke="var(--blue-b)" stroke-width="14" fill="none" stroke-linecap="round"/>

                <rect x="210" y="145" width="30" height="26" rx="8" fill="var(--blue-c)"/>
                <rect x="236" y="136" width="40" height="44" rx="10" fill="#0396e2"/>
                <rect x="270" y="130" width="58" height="56" rx="12" fill="#41baf0"/>
                <rect x="325" y="138" width="28" height="40" rx="8" fill="var(--blue-c)"/>
                <rect x="350" y="143" width="14" height="9" rx="3" fill="var(--blue-c)"/>
                <rect x="350" y="156" width="14" height="9" rx="3" fill="var(--blue-c)"/>
                <rect x="350" y="169" width="14" height="9" rx="3" fill="var(--blue-c)"/>

                <rect x="555" y="145" width="30" height="26" rx="8" fill="var(--green-c)"/>
                <rect x="518" y="136" width="40" height="44" rx="10" fill="#00b83f"/>
                <rect x="462" y="130" width="58" height="56" rx="12" fill="#20dd58"/>
                <rect x="437" y="138" width="28" height="40" rx="8" fill="var(--green-b)"/>

                <path d="M585 158 h30 c0 0 10 0 10 -10 v-10 c0 -10 10 -10 10 -10 h285" stroke="var(--green-a)" stroke-width="14" fill="none" stroke-linecap="round"/>

                <rect x="414" y="126" width="10" height="12" rx="2" fill="var(--blue-b)"/>
                <rect x="410" y="144" width="14" height="12" rx="2" fill="var(--blue-b)"/>
                <rect x="414" y="162" width="10" height="12" rx="2" fill="var(--blue-b)"/>

                <rect x="496" y="126" width="10" height="12" rx="2" fill="var(--green-a)"/>
                <rect x="496" y="144" width="14" height="12" rx="2" fill="var(--green-a)"/>
                <rect x="496" y="162" width="10" height="12" rx="2" fill="var(--green-a)"/>
            </svg>
        </div>
    </main>
</body>
</html>
