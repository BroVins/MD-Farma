<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pemulihan — MD Farma</title>

    <style>
        :root {
            --green-950:#1f2937;
            --green-900:#172554;
            --green-800:#1e3a8a;
            --green-700:#1238cc;
            --green-600:#2a55df;
            --green-100:#dbeafe;
            --green-50:#eff6ff;
            --red-800:#991b1b;
            --slate-950:#0f172a;
            --slate-700:#334155;
            --slate-500:#64748b;
            --slate-300:#cbd5e1;
            --slate-200:#e2e8f0;
            --slate-100:#f1f5f9;
            --white:#fff;
        }

        * { box-sizing:border-box; }

        body {
            min-height:100vh;
            margin:0;
            font-family:Inter,ui-sans-serif,system-ui,-apple-system,
                BlinkMacSystemFont,"Segoe UI",sans-serif;
            color:var(--slate-950);
            background:
                radial-gradient(circle at 88% 5%,rgba(59, 130, 246, .18),transparent 25%),
                #f8fafc;
        }

        .topbar {
            border-bottom:1px solid rgba(203,213,225,.72);
            background:rgba(255,255,255,.9);
            backdrop-filter:blur(14px);
        }

        nav {
            width:min(720px,92%);
            min-height:70px;
            margin:auto;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:18px;
        }

        nav a { text-decoration:none; }

        .brand {
            display:flex;
            align-items:center;
            gap:10px;
            color:var(--slate-950);
            font-weight:900;
        }

        .brand-mark {
            width:36px;
            height:36px;
            display:grid;
            place-items:center;
            border-radius:11px;
            background:var(--green-700);
            color:#fff;
            font-size:21px;
        }

        .page {
            width:min(620px,92%);
            margin:50px auto 70px;
        }

        .card {
            overflow:hidden;
            border:1px solid var(--slate-200);
            border-radius:25px;
            background:#fff;
            box-shadow:0 24px 70px rgba(15,23,42,.1);
        }

        .head {
            padding:30px;
            color:#fff;
            background:
                radial-gradient(circle at 100% 0%,rgba(255,255,255,.14),transparent 38%),
                linear-gradient(145deg,var(--green-800),var(--green-950));
        }

        .eyebrow {
            margin:0 0 9px;
            color:#bfdbfe;
            font-size:11px;
            font-weight:900;
            letter-spacing:.08em;
            text-transform:uppercase;
        }

        h1 {
            margin:0;
            font-size:clamp(28px,5vw,38px);
            line-height:1.08;
            letter-spacing:-.04em;
        }

        .head p:last-child {
            margin:13px 0 0;
            color:#dbeafe;
            font-size:13px;
            line-height:1.65;
        }

        .content { padding:28px 30px 30px; }

        .error-box {
            margin-bottom:20px;
            padding:13px 15px;
            border:1px solid #fecaca;
            border-radius:12px;
            background:#fef2f2;
            color:var(--red-800);
            font-size:12px;
        }

        .summary {
            overflow:hidden;
            border:1px solid var(--slate-200);
            border-radius:16px;
        }

        .row {
            padding:14px 16px;
            display:grid;
            grid-template-columns:150px minmax(0,1fr);
            gap:16px;
            align-items:center;
        }

        .row + .row { border-top:1px solid var(--slate-200); }

        .row span {
            color:var(--slate-500);
            font-size:12px;
            font-weight:800;
        }

        .row strong {
            font-size:13px;
            overflow-wrap:anywhere;
        }

        .confirm-box {
            margin-top:20px;
            padding:15px;
            display:flex;
            gap:11px;
            align-items:flex-start;
            border:1px solid #bfdbfe;
            border-radius:13px;
            background:var(--green-50);
            color:var(--green-900);
            font-size:12px;
            line-height:1.55;
        }

        .confirm-box input {
            width:18px;
            height:18px;
            margin:1px 0 0;
            flex:0 0 auto;
            accent-color:var(--green-700);
        }

        .actions {
            margin-top:20px;
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:12px;
        }

        .button {
            min-height:49px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:12px;
            text-decoration:none;
            font-size:13px;
            font-weight:900;
            cursor:pointer;
        }

        .button.primary {
            border:0;
            background:linear-gradient(145deg,var(--green-600),var(--green-800));
            color:#fff;
            box-shadow:0 13px 30px rgba(18, 56, 204, .2);
        }

        .button.secondary {
            border:1px solid var(--slate-300);
            background:#fff;
            color:var(--slate-700);
        }

        .privacy {
            margin:20px 0 0;
            padding:13px 15px;
            border-radius:12px;
            background:var(--slate-100);
            color:var(--slate-500);
            font-size:11px;
            line-height:1.55;
        }

        .privacy strong {
            display:block;
            margin-bottom:3px;
            color:var(--slate-700);
        }

        @media (max-width:570px) {
            .page { margin-top:28px; }
            .head,.content { padding:24px 22px; }
            .card { border-radius:20px; }
            .row { grid-template-columns:1fr; gap:5px; }
            .actions { grid-template-columns:1fr; }
        }
    </style>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/md-farma-logo.jpeg') }}">
    <link rel="stylesheet" href="{{ asset('css/md-farma-logo-theme.css') }}">
</head>
<body>
    <header class="topbar">
        <nav>
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/md-farma-logo.jpeg') }}" alt="" width="52" height="52"></span>
                <span>MD Farma</span>
            </a>
        </nav>
    </header>

    <main class="page">
        <section class="card" aria-labelledby="confirm-title">
            <header class="head">
                <p class="eyebrow">Konfirmasi terakhir</p>
                <h1 id="confirm-title">Apakah ini riwayat Anda?</h1>
                <p>
                    Data ditampilkan secara tersamarkan. Pastikan informasi
                    berikut sesuai sebelum perangkat ini ditautkan.
                </p>
            </header>

            <div class="content">
                @if ($errors->any())
                    <div class="error-box" role="alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="summary">
                    <div class="row">
                        <span>Nama pasien</span>
                        <strong>{{ $maskedName }}</strong>
                    </div>
                    <div class="row">
                        <span>Nomor kontak</span>
                        <strong>{{ $maskedPhone }}</strong>
                    </div>
                    <div class="row">
                        <span>Konsultasi terakhir</span>
                        <strong>{{ $consultationDate }}</strong>
                    </div>
                    <div class="row">
                        <span>Jalur awal</span>
                        <strong>{{ $consultationType }}</strong>
                    </div>
                </div>

                <form
                    action="{{ route('consultation.recovery.confirm') }}"
                    method="POST"
                >
                    @csrf

                    <label class="confirm-box" for="confirm_history">
                        <input
                            id="confirm_history"
                            type="checkbox"
                            name="confirm_history"
                            value="1"
                            required
                        >
                        <span>
                            Saya memastikan data tersamarkan di atas merupakan
                            riwayat konsultasi saya dan perangkat ini aman digunakan.
                        </span>
                    </label>

                    <div class="actions">
                        <a
                            class="button secondary"
                            href="{{ route('consultation.recovery.show') }}"
                        >
                            Bukan Riwayat Saya
                        </a>
                        <button class="button primary" type="submit">
                            Tautkan Perangkat Ini
                        </button>
                    </div>
                </form>

                <div class="privacy">
                    <strong>Perangkat baru, riwayat yang sama</strong>
                    Sistem akan membuat cookie perangkat baru dan menghubungkannya
                    ke riwayat lama. Cookie pada perangkat sebelumnya tidak dipindahkan.
                </div>
            </div>
        </section>
    </main>
</body>
</html>
