<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Privasi — MD Farma</title>
    <style>
        :root {
            --green-950:#1f2937;
            --green-800:#1e3a8a;
            --green-700:#1238cc;
            --green-100:#dbeafe;
            --green-50:#eff6ff;
            --slate-950:#0f172a;
            --slate-700:#334155;
            --slate-500:#64748b;
            --slate-300:#cbd5e1;
            --slate-200:#e2e8f0;
            --white:#fff;
        }

        * { box-sizing:border-box; }

        body {
            margin:0;
            min-height:100vh;
            font-family:Inter,ui-sans-serif,system-ui,-apple-system,
                BlinkMacSystemFont,"Segoe UI",sans-serif;
            color:var(--slate-950);
            background:
                radial-gradient(circle at 92% 2%,rgba(59, 130, 246, .14),transparent 24%),
                #f8fafc;
        }

        .topbar {
            position:sticky;
            top:0;
            z-index:10;
            border-bottom:1px solid rgba(203,213,225,.75);
            background:rgba(255,255,255,.92);
            backdrop-filter:blur(14px);
        }

        nav {
            width:min(1060px,92%);
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
            color:var(--green-800);
            font-size:13px;
            font-weight:850;
        }

        main {
            width:min(920px,92%);
            margin:48px auto 80px;
        }

        .hero {
            padding:34px;
            border:1px solid var(--slate-200);
            border-radius:24px;
            background:linear-gradient(145deg,#fff,var(--green-50));
            box-shadow:0 20px 55px rgba(15,23,42,.08);
        }

        .eyebrow {
            display:inline-flex;
            padding:7px 11px;
            border-radius:999px;
            background:var(--green-100);
            color:var(--green-800);
            font-size:11px;
            font-weight:900;
            letter-spacing:.06em;
            text-transform:uppercase;
        }

        h1 {
            max-width:720px;
            margin:18px 0 12px;
            color:var(--green-950);
            font-size:clamp(34px,6vw,58px);
            line-height:1.03;
            letter-spacing:-.045em;
        }

        .lead {
            max-width:760px;
            margin:0;
            color:var(--slate-500);
            font-size:15px;
            line-height:1.8;
        }

        .version {
            margin-top:18px;
            color:var(--slate-500);
            font-size:12px;
        }

        .content {
            margin-top:24px;
            display:grid;
            gap:16px;
        }

        section {
            padding:24px 26px;
            border:1px solid var(--slate-200);
            border-radius:18px;
            background:var(--white);
        }

        h2 {
            margin:0 0 10px;
            color:var(--green-950);
            font-size:20px;
        }

        p, li {
            color:var(--slate-700);
            font-size:14px;
            line-height:1.75;
        }

        ul {
            margin:10px 0 0;
            padding-left:20px;
        }

        .notice {
            border-color:#bfdbfe;
            background:var(--green-50);
        }

        .actions {
            margin-top:24px;
            display:flex;
            flex-wrap:wrap;
            gap:10px;
        }

        .button {
            min-height:44px;
            padding:0 17px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border-radius:11px;
            background:var(--green-700);
            color:#fff;
            font-size:13px;
            font-weight:900;
            text-decoration:none;
        }

        .button.secondary {
            border:1px solid var(--slate-300);
            background:#fff;
            color:var(--slate-700);
        }

        @media (max-width:640px) {
            main { margin-top:26px; }
            .hero { padding:24px 20px; }
            section { padding:21px 19px; }
            .button { width:100%; }
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
            <a class="back" href="{{ route('consultation.create') }}">
                Kembali ke konsultasi
            </a>
        </nav>
    </header>

    <main>
        <div class="hero">
            <span class="eyebrow">Privasi konsultasi</span>
            <h1>Kebijakan Privasi MD Farma</h1>
            <p class="lead">
                Halaman ini menjelaskan data yang diproses saat Anda memakai
                layanan konsultasi, alasan penyimpanannya, masa akses pasien,
                dan cara MD Farma membatasi penggunaan data tersebut.
            </p>
            <p class="version">
                Versi kebijakan: <strong>{{ $policyVersion }}</strong>
            </p>
        </div>

        <div class="content">
            <section>
                <h2>Data yang diproses</h2>
                <p>
                    MD Farma dapat memproses nama, umur, nomor kontak, pilihan
                    jenis konsultasi, isi percakapan, resep atau gambar yang
                    dikirim, dokumen PDF, data perangkat tepercaya, serta catatan
                    pelayanan yang dibuat oleh apoteker.
                </p>
            </section>

            <section>
                <h2>Tujuan pemrosesan</h2>
                <ul>
                    <li>Memberikan layanan konsultasi kefarmasian.</li>
                    <li>Menjaga keamanan akses dan riwayat konsultasi.</li>
                    <li>Mendokumentasikan klasifikasi, skrining, dan hasil pelayanan.</li>
                    <li>Menangani permintaan salinan arsip, audit, dan kebutuhan kepatuhan.</li>
                    <li>Meningkatkan mutu dan keandalan layanan MD Farma.</li>
                </ul>
            </section>

            <section class="notice">
                <h2>Masa akses pasien dan arsip internal</h2>
                <p>
                    Isi chat konsultasi yang sudah selesai dapat dibuka melalui
                    dashboard pasien selama 60 hari. Setelah periode tersebut,
                    chat tidak lagi ditampilkan kepada pasien, tetapi dapat tetap
                    dikelola sebagai arsip internal berdasarkan kebijakan retensi,
                    kebutuhan pelayanan, audit, sengketa, dan kewajiban yang berlaku.
                </p>
            </section>

            <section>
                <h2>Keamanan dan pembatasan akses</h2>
                <p>
                    Riwayat pasien dilindungi menggunakan Password Riwayat dan
                    token perangkat. Akses internal dibatasi pada admin/apoteker
                    yang terautentikasi. Jangan membagikan password, perangkat,
                    tautan chat, atau salinan percakapan kepada pihak lain.
                </p>
            </section>

            <section>
                <h2>Lampiran</h2>
                <p>
                    Demi mengurangi risiko keamanan, layanan hanya menerima
                    gambar JPG, PNG, WebP, dan dokumen PDF. File disimpan pada
                    penyimpanan privat dan hanya dapat dibuka melalui pemeriksaan
                    akses aplikasi.
                </p>
            </section>

            <section>
                <h2>Hak dan permintaan terkait data</h2>
                <p>
                    Anda dapat meminta koreksi data profil atau mengajukan
                    salinan konsultasi yang sudah diarsipkan melalui dashboard.
                    Permintaan tertentu memerlukan verifikasi manual sebelum
                    diproses atau diserahkan.
                </p>
            </section>
        </div>

        <div class="actions">
            <a class="button" href="{{ route('consultation.create') }}">
                Lanjut ke konsultasi
            </a>
            <a class="button secondary" href="{{ route('home') }}">
                Kembali ke beranda
            </a>
        </div>
    </main>
</body>
</html>
