<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pulihkan Riwayat Konsultasi — MD Farma</title>

    <style>
        :root {
            --green-950:#052e2b;
            --green-900:#064e3b;
            --green-800:#065f46;
            --green-700:#047857;
            --green-600:#059669;
            --green-100:#d1fae5;
            --green-50:#ecfdf5;
            --amber-100:#fef3c7;
            --amber-800:#92400e;
            --red-100:#fee2e2;
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
                radial-gradient(circle at 88% 4%,rgba(16,185,129,.18),transparent 25%),
                #f8fafc;
        }

        .topbar {
            border-bottom:1px solid rgba(203,213,225,.72);
            background:rgba(255,255,255,.9);
            backdrop-filter:blur(14px);
        }

        nav {
            width:min(980px,92%);
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

        .back {
            color:var(--slate-700);
            font-size:13px;
            font-weight:800;
        }

        .page {
            width:min(980px,92%);
            margin:46px auto 70px;
            display:grid;
            grid-template-columns:minmax(250px,.75fr) minmax(0,1.25fr);
            gap:30px;
            align-items:start;
        }

        .intro {
            position:sticky;
            top:26px;
            padding:30px;
            border-radius:24px;
            color:#fff;
            background:
                radial-gradient(circle at 100% 0%,rgba(255,255,255,.14),transparent 38%),
                linear-gradient(145deg,var(--green-800),var(--green-950));
            box-shadow:0 24px 70px rgba(6,78,59,.18);
        }

        .eyebrow {
            margin:0 0 10px;
            color:#a7f3d0;
            font-size:11px;
            font-weight:900;
            letter-spacing:.08em;
            text-transform:uppercase;
        }

        h1 {
            margin:0;
            font-size:clamp(30px,4vw,43px);
            line-height:1.06;
            letter-spacing:-.045em;
        }

        .intro > p:last-of-type {
            margin:16px 0 24px;
            color:#d1fae5;
            font-size:13px;
            line-height:1.65;
        }

        .steps { display:grid; gap:14px; }

        .step {
            display:flex;
            gap:12px;
            align-items:flex-start;
        }

        .step span {
            flex:0 0 auto;
            width:29px;
            height:29px;
            display:grid;
            place-items:center;
            border-radius:9px;
            background:rgba(255,255,255,.13);
            color:#a7f3d0;
            font-size:11px;
            font-weight:900;
        }

        .step strong {
            display:block;
            margin-bottom:3px;
            font-size:13px;
        }

        .step small {
            color:#a7f3d0;
            line-height:1.45;
        }

        .card {
            padding:32px;
            border:1px solid var(--slate-200);
            border-radius:24px;
            background:#fff;
            box-shadow:0 18px 60px rgba(15,23,42,.08);
        }

        .card h2 {
            margin:0 0 7px;
            font-size:27px;
            letter-spacing:-.03em;
        }

        .lead {
            margin:0 0 24px;
            color:var(--slate-500);
            font-size:13px;
            line-height:1.6;
        }

        .notice,
        .error-box {
            margin-bottom:20px;
            padding:13px 15px;
            border-radius:12px;
            font-size:12px;
            line-height:1.55;
        }

        .notice {
            border:1px solid #fde68a;
            background:#fffbeb;
            color:var(--amber-800);
        }

        .error-box {
            border:1px solid #fecaca;
            background:#fef2f2;
            color:var(--red-800);
        }

        .error-box ul {
            margin:0;
            padding-left:18px;
        }

        .field + .field { margin-top:17px; }

        label {
            display:block;
            margin-bottom:8px;
            color:var(--slate-700);
            font-size:12px;
            font-weight:900;
        }

        input {
            width:100%;
            min-height:49px;
            padding:0 13px;
            border:1px solid var(--slate-300);
            border-radius:11px;
            background:#fff;
            color:var(--slate-950);
            outline:none;
            font:inherit;
            font-size:14px;
            transition:.18s ease;
        }

        input:focus {
            border-color:var(--green-600);
            box-shadow:0 0 0 4px rgba(5,150,105,.12);
        }

        .hint {
            display:block;
            margin-top:7px;
            color:var(--slate-500);
            font-size:11px;
            line-height:1.5;
        }

        .submit {
            width:100%;
            min-height:50px;
            margin-top:22px;
            border:0;
            border-radius:12px;
            background:linear-gradient(145deg,var(--green-600),var(--green-800));
            color:#fff;
            font-weight:900;
            cursor:pointer;
            box-shadow:0 13px 30px rgba(4,120,87,.2);
        }

        .privacy {
            margin:20px 0 0;
            padding:14px 15px;
            border-radius:12px;
            background:var(--slate-100);
            color:var(--slate-500);
            font-size:11px;
            line-height:1.55;
        }

        .privacy strong {
            display:block;
            margin-bottom:4px;
            color:var(--slate-700);
        }

        @media (max-width:780px) {
            .page { grid-template-columns:1fr; }
            .intro { position:static; }
        }

        @media (max-width:570px) {
            .page { margin-top:28px; }
            .intro,.card { padding:24px 22px; border-radius:20px; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <nav>
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark">+</span>
                <span>MD Farma</span>
            </a>
            <a class="back" href="{{ route('consultation.create') }}">
                ← Konsultasi baru
            </a>
        </nav>
    </header>

    <main class="page">
        <aside class="intro">
            <p class="eyebrow">Pemulihan perangkat</p>
            <h1>Tautkan kembali riwayat Anda.</h1>
            <p>
                Gunakan data dari konsultasi terakhir. Sistem tidak membuka
                riwayat hanya berdasarkan nama atau nomor telepon.
            </p>

            <div class="steps">
                <div class="step">
                    <span>1</span>
                    <div>
                        <strong>Masukkan data terakhir</strong>
                        <small>Nomor kontak dan tanggal konsultasi terakhir.</small>
                    </div>
                </div>
                <div class="step">
                    <span>2</span>
                    <div>
                        <strong>Verifikasi password</strong>
                        <small>Password Riwayat menjadi bukti utama akses.</small>
                    </div>
                </div>
                <div class="step">
                    <span>3</span>
                    <div>
                        <strong>Konfirmasi data tersamarkan</strong>
                        <small>Perangkat baru ditautkan setelah Anda mengonfirmasi.</small>
                    </div>
                </div>
            </div>
        </aside>

        <section class="card" aria-labelledby="recovery-title">
            <h2 id="recovery-title">Pulihkan Riwayat Konsultasi</h2>
            <p class="lead">
                Fitur ini digunakan saat Anda berganti perangkat atau menghapus
                data browser. Masukkan data yang digunakan pada konsultasi paling baru.
            </p>

            @if (session('warning'))
                <div class="notice">{{ session('warning') }}</div>
            @endif

            @if ($errors->any())
                <div class="error-box" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('consultation.recovery.verify') }}"
                method="POST"
            >
                @csrf

                <div class="field">
                    <label for="no_hp">Nomor HP terakhir</label>
                    <input
                        id="no_hp"
                        type="tel"
                        name="no_hp"
                        value="{{ old('no_hp') }}"
                        maxlength="25"
                        autocomplete="tel"
                        inputmode="tel"
                        placeholder="Contoh: 081234567890"
                        required
                        autofocus
                    >
                    <span class="hint">
                        Gunakan nomor yang tercatat pada konsultasi terakhir.
                    </span>
                </div>

                <div class="field">
                    <label for="tanggal_konsultasi_terakhir">
                        Tanggal membuat konsultasi terakhir
                    </label>
                    <input
                        id="tanggal_konsultasi_terakhir"
                        type="date"
                        name="tanggal_konsultasi_terakhir"
                        value="{{ old('tanggal_konsultasi_terakhir') }}"
                        max="{{ now()->timezone(config('analytics.timezone', 'Asia/Jakarta'))->format('Y-m-d') }}"
                        required
                    >
                    <span class="hint">
                        Masukkan tanggal saat form konsultasi terakhir dibuat,
                        bukan tanggal percakapan ditutup.
                    </span>
                </div>

                <div class="field">
                    <label for="password_riwayat">Password Riwayat</label>
                    <input
                        id="password_riwayat"
                        type="password"
                        name="password_riwayat"
                        maxlength="128"
                        autocomplete="current-password"
                        placeholder="Masukkan password Anda"
                        required
                    >
                    <span class="hint">
                        Percobaan dibatasi untuk melindungi riwayat konsultasi.
                    </span>
                </div>

                <button class="submit" type="submit">
                    Periksa Data Pemulihan
                </button>
            </form>

            <div class="privacy">
                <strong>Respons dibuat generik untuk keamanan</strong>
                Sistem tidak akan memberi tahu bagian data mana yang salah dan
                tidak menampilkan daftar pasien yang memiliki nomor serupa.
            </div>
        </section>
    </main>
</body>
</html>
