<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>MD Farma — Konsultasi Apoteker</title>

    <style>
        :root {
            --green-950:#052e2b;
            --green-900:#064e3b;
            --green-800:#065f46;
            --green-700:#047857;
            --green-600:#059669;
            --green-100:#d1fae5;
            --green-50:#ecfdf5;
            --slate-950:#0f172a;
            --slate-700:#334155;
            --slate-500:#64748b;
            --slate-300:#cbd5e1;
            --slate-100:#f1f5f9;
            --white:#fff;
            --shadow:0 22px 60px rgba(15,23,42,.11);
        }

        * { box-sizing:border-box; }

        html { scroll-behavior:smooth; }

        body {
            margin:0;
            font-family:Inter,ui-sans-serif,system-ui,-apple-system,
                BlinkMacSystemFont,"Segoe UI",sans-serif;
            color:var(--slate-950);
            background:#f8fafc;
        }

        a { color:inherit; }

        .topbar {
            position:sticky;
            top:0;
            z-index:20;
            border-bottom:1px solid rgba(203,213,225,.75);
            background:rgba(255,255,255,.91);
            backdrop-filter:blur(14px);
        }

        .nav {
            width:min(1160px,92%);
            min-height:70px;
            margin:auto;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:20px;
        }

        .brand {
            display:flex;
            align-items:center;
            gap:11px;
            text-decoration:none;
            font-weight:900;
            letter-spacing:-.02em;
        }

        .brand-mark {
            width:38px;
            height:38px;
            display:grid;
            place-items:center;
            border-radius:12px;
            background:linear-gradient(145deg,var(--green-600),var(--green-800));
            color:#fff;
            box-shadow:0 9px 22px rgba(5,150,105,.27);
            font-size:22px;
        }

        .nav-links {
            display:flex;
            align-items:center;
            gap:22px;
            font-size:14px;
            font-weight:700;
        }

        .nav-links a {
            text-decoration:none;
            color:var(--slate-700);
        }

        .nav-links .admin {
            padding:10px 15px;
            border:1px solid var(--slate-300);
            border-radius:10px;
            background:#fff;
        }

        .hero {
            position:relative;
            overflow:hidden;
            padding:86px 0 74px;
            background:
                radial-gradient(circle at 85% 10%,rgba(16,185,129,.18),transparent 27%),
                linear-gradient(135deg,#f8fafc 0%,#ecfdf5 100%);
        }

        .hero::after {
            content:"";
            position:absolute;
            width:420px;
            height:420px;
            right:-180px;
            bottom:-250px;
            border-radius:50%;
            border:70px solid rgba(5,150,105,.06);
        }

        .hero-grid {
            position:relative;
            z-index:1;
            width:min(1160px,92%);
            margin:auto;
            display:grid;
            grid-template-columns:minmax(0,1.13fr) minmax(330px,.87fr);
            gap:64px;
            align-items:center;
        }

        .eyebrow {
            display:inline-flex;
            align-items:center;
            gap:8px;
            margin:0 0 18px;
            padding:7px 11px;
            border-radius:999px;
            background:var(--green-100);
            color:var(--green-900);
            font-size:12px;
            font-weight:900;
            letter-spacing:.06em;
            text-transform:uppercase;
        }

        .eyebrow::before {
            content:"";
            width:7px;
            height:7px;
            border-radius:50%;
            background:var(--green-600);
        }

        h1 {
            max-width:760px;
            margin:0;
            font-size:clamp(42px,6vw,72px);
            line-height:1.02;
            letter-spacing:-.055em;
        }

        .accent { color:var(--green-700); }

        .lead {
            max-width:670px;
            margin:23px 0 30px;
            color:var(--slate-500);
            font-size:18px;
            line-height:1.72;
        }

        .actions {
            display:flex;
            align-items:center;
            gap:13px;
            flex-wrap:wrap;
        }

        .button {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-height:48px;
            padding:0 20px;
            border-radius:12px;
            text-decoration:none;
            font-size:14px;
            font-weight:900;
        }

        .button.primary {
            background:var(--green-700);
            color:#fff;
            box-shadow:0 13px 30px rgba(4,120,87,.22);
        }

        .button.secondary {
            border:1px solid var(--slate-300);
            background:#fff;
            color:var(--slate-700);
        }

        .trust-row {
            margin-top:28px;
            display:flex;
            flex-wrap:wrap;
            gap:17px;
            color:var(--slate-500);
            font-size:12px;
            font-weight:700;
        }

        .trust-row span {
            display:flex;
            align-items:center;
            gap:7px;
        }

        .trust-row i {
            width:18px;
            height:18px;
            display:grid;
            place-items:center;
            border-radius:50%;
            background:var(--green-100);
            color:var(--green-800);
            font-style:normal;
            font-size:10px;
        }

        .preview {
            position:relative;
            padding:24px;
            border:1px solid rgba(255,255,255,.75);
            border-radius:28px;
            background:rgba(255,255,255,.82);
            box-shadow:var(--shadow);
            backdrop-filter:blur(16px);
        }

        .preview-head {
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            margin-bottom:22px;
        }

        .preview-title strong {
            display:block;
            margin-bottom:4px;
            font-size:16px;
        }

        .preview-title span {
            color:var(--slate-500);
            font-size:12px;
        }

        .online {
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:7px 10px;
            border-radius:999px;
            background:var(--green-50);
            color:var(--green-800);
            font-size:11px;
            font-weight:900;
        }

        .online::before {
            content:"";
            width:7px;
            height:7px;
            border-radius:50%;
            background:#16a34a;
        }

        .mock-chat {
            min-height:300px;
            padding:17px;
            border-radius:18px;
            background:#f8fafc;
        }

        .date {
            margin:2px 0 17px;
            color:var(--slate-500);
            text-align:center;
            font-size:10px;
            font-weight:800;
        }

        .bubble {
            width:78%;
            margin-bottom:13px;
            padding:12px 14px;
            border-radius:5px 16px 16px 16px;
            background:linear-gradient(145deg,var(--green-600),var(--green-800));
            color:#fff;
            font-size:12px;
            line-height:1.52;
            box-shadow:0 7px 18px rgba(4,120,87,.16);
        }

        .bubble.admin {
            margin-left:auto;
            border-radius:16px 16px 5px 16px;
            background:linear-gradient(145deg,#334155,#1e293b);
        }

        .bubble small {
            display:block;
            margin-top:6px;
            text-align:right;
            opacity:.72;
        }

        .section {
            width:min(1160px,92%);
            margin:auto;
            padding:78px 0;
        }

        .section-head {
            max-width:680px;
            margin-bottom:34px;
        }

        .section-head h2 {
            margin:0 0 12px;
            font-size:clamp(30px,4vw,44px);
            letter-spacing:-.04em;
        }

        .section-head p {
            margin:0;
            color:var(--slate-500);
            line-height:1.68;
        }

        .features {
            display:grid;
            grid-template-columns:repeat(3,minmax(0,1fr));
            gap:18px;
        }

        .feature {
            padding:25px;
            border:1px solid #e2e8f0;
            border-radius:20px;
            background:#fff;
            box-shadow:0 9px 30px rgba(15,23,42,.045);
        }

        .number {
            width:40px;
            height:40px;
            display:grid;
            place-items:center;
            border-radius:12px;
            background:var(--green-50);
            color:var(--green-800);
            font-weight:950;
        }

        .feature h3 {
            margin:20px 0 9px;
            font-size:18px;
        }

        .feature p {
            margin:0;
            color:var(--slate-500);
            font-size:14px;
            line-height:1.65;
        }

        .notice {
            width:min(1160px,92%);
            margin:0 auto 78px;
            padding:24px 27px;
            display:flex;
            align-items:flex-start;
            gap:17px;
            border:1px solid #fed7aa;
            border-radius:18px;
            background:#fff7ed;
            color:#9a3412;
        }

        .notice strong { display:block; margin-bottom:5px; }
        .notice p { margin:0; font-size:13px; line-height:1.6; }

        footer {
            padding:28px 4%;
            border-top:1px solid #e2e8f0;
            color:var(--slate-500);
            text-align:center;
            font-size:12px;
            background:#fff;
        }

        @media (max-width:860px) {
            .hero-grid { grid-template-columns:1fr; }
            .preview { max-width:600px; }
            .features { grid-template-columns:1fr; }
        }

        @media (max-width:620px) {
            .nav-links > a:not(.admin) { display:none; }
            .hero { padding-top:58px; }
            .hero-grid { gap:42px; }
            .lead { font-size:16px; }
            .actions .button { width:100%; }
            .preview { padding:16px; border-radius:21px; }
            .notice { flex-direction:column; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <nav class="nav">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark">+</span>
                <span>MD Farma</span>
            </a>

            <div class="nav-links">
                <a href="#alur">Cara Kerja</a>
                <a href="#keamanan">Keamanan</a>
                <a class="admin" href="{{ route('admin.login') }}">
                    Login Admin
                </a>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-grid">
                <div>
                    <p class="eyebrow">Konsultasi farmasi digital</p>
                    <h1>
                        Konsultasikan kebutuhan obat secara
                        <span class="accent">lebih terarah.</span>
                    </h1>
                    <p class="lead">
                        Isi data konsultasi, sampaikan pertanyaan, lalu
                        terhubung dengan admin apotek melalui chat realtime
                        yang dilengkapi waktu pengiriman secara akurat.
                    </p>

                    <div class="actions">
                        <a
                            class="button primary"
                            href="{{ route('consultation.create') }}"
                        >
                            Mulai Konsultasi
                        </a>
                        <a class="button secondary" href="#alur">
                            Lihat Alur Layanan
                        </a>
                    </div>

                    <div class="trust-row" id="keamanan">
                        <span><i>✓</i>Sesi konsultasi privat</span>
                        <span><i>✓</i>Chat realtime</span>
                        <span><i>✓</i>Lampiran tersimpan privat</span>
                    </div>
                </div>

                <aside class="preview" aria-label="Pratinjau chat">
                    <div class="preview-head">
                        <div class="preview-title">
                            <strong>Konsultasi Aktif</strong>
                            <span>Riwayat pesan tersimpan otomatis</span>
                        </div>
                        <span class="online">Realtime</span>
                    </div>

                    <div class="mock-chat">
                        <div class="date">Contoh riwayat waktu pesan</div>
                        <div class="bubble">
                            Saya ingin berkonsultasi mengenai aturan minum obat.
                            <small>09.14 WIB</small>
                        </div>
                        <div class="bubble admin">
                            Silakan sampaikan nama obat dan aturan dari dokter
                            yang tertera pada resep.
                            <small>09.16 WIB</small>
                        </div>
                        <div class="bubble">
                            Baik, saya akan mengirimkan detailnya.
                            <small>09.17 WIB</small>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <section class="section" id="alur">
            <div class="section-head">
                <h2>Alur konsultasi yang sederhana</h2>
                <p>
                    Pasien tidak perlu membuat akun. Sistem membentuk sesi
                    anonim yang terikat pada browser dan konsultasi terkait.
                </p>
            </div>

            <div class="features">
                <article class="feature">
                    <span class="number">01</span>
                    <h3>Isi data konsultasi</h3>
                    <p>
                        Masukkan data dasar dan pilih kategori resep atau
                        non resep sesuai kebutuhan konsultasi.
                    </p>
                </article>

                <article class="feature">
                    <span class="number">02</span>
                    <h3>Masuk ke ruang chat</h3>
                    <p>
                        Sistem membuat ruang konsultasi privat dengan alamat
                        acak dan akses yang terikat pada sesi pasien.
                    </p>
                </article>

                <article class="feature">
                    <span class="number">03</span>
                    <h3>Terima balasan admin</h3>
                    <p>
                        Pesan tampil secara realtime, lengkap dengan hari,
                        tanggal, bulan, tahun, dan jam pengiriman.
                    </p>
                </article>
            </div>
        </section>

        <aside class="notice">
            <span class="number">!</span>
            <div>
                <strong>Bukan layanan kegawatdaruratan</strong>
                <p>
                    Untuk kondisi darurat atau gejala berat, segera hubungi
                    fasilitas kesehatan atau layanan darurat terdekat.
                </p>
            </div>
        </aside>
    </main>

    <footer>
        MD Farma — Prototipe sistem konsultasi farmasi berbasis web.
    </footer>
</body>
</html>
