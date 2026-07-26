<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Form Konsultasi — MD Farma</title>

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
                radial-gradient(circle at 90% 4%,rgba(16,185,129,.17),transparent 24%),
                #f8fafc;
        }

        .topbar {
            border-bottom:1px solid rgba(203,213,225,.7);
            background:rgba(255,255,255,.9);
            backdrop-filter:blur(14px);
        }

        nav {
            width:min(1120px,92%);
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
            width:min(1120px,92%);
            margin:46px auto 70px;
            display:grid;
            grid-template-columns:minmax(270px,.75fr) minmax(0,1.25fr);
            gap:35px;
            align-items:start;
        }

        .intro {
            position:sticky;
            top:28px;
            padding:34px;
            border-radius:24px;
            color:#fff;
            background:
                radial-gradient(circle at 100% 0%,rgba(255,255,255,.14),transparent 35%),
                linear-gradient(145deg,var(--green-800),var(--green-950));
            box-shadow:0 25px 70px rgba(6,78,59,.2);
        }

        .eyebrow {
            margin:0 0 12px;
            color:#a7f3d0;
            font-size:11px;
            font-weight:900;
            letter-spacing:.08em;
            text-transform:uppercase;
        }

        .intro h1 {
            margin:0;
            font-size:clamp(31px,4vw,47px);
            line-height:1.04;
            letter-spacing:-.045em;
        }

        .intro > p:not(.eyebrow) {
            margin:18px 0 26px;
            color:#d1fae5;
            line-height:1.68;
            font-size:14px;
        }

        .steps {
            display:grid;
            gap:15px;
        }

        .step {
            display:flex;
            gap:12px;
            align-items:flex-start;
        }

        .step span {
            flex:0 0 auto;
            width:30px;
            height:30px;
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

        .form-card {
            padding:34px;
            border:1px solid var(--slate-200);
            border-radius:24px;
            background:#fff;
            box-shadow:0 18px 60px rgba(15,23,42,.08);
        }

        .form-head {
            margin-bottom:26px;
        }

        .form-head h2 {
            margin:0 0 7px;
            font-size:27px;
            letter-spacing:-.03em;
        }

        .form-head p {
            margin:0;
            color:var(--slate-500);
            font-size:13px;
            line-height:1.55;
        }

        .error-box {
            margin-bottom:22px;
            padding:14px 16px;
            border:1px solid #fecaca;
            border-radius:12px;
            background:#fef2f2;
            color:#991b1b;
            font-size:13px;
        }

        .error-box strong { display:block; margin-bottom:6px; }
        .error-box ul { margin:0; padding-left:19px; }

        .grid {
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:18px;
        }

        .field.full { grid-column:1 / -1; }

        label {
            display:block;
            margin-bottom:8px;
            color:var(--slate-700);
            font-size:12px;
            font-weight:900;
        }

        input,
        select {
            width:100%;
            min-height:47px;
            padding:0 13px;
            border:1px solid var(--slate-300);
            border-radius:10px;
            background:#fff;
            color:var(--slate-950);
            outline:none;
            font:inherit;
            font-size:14px;
            transition:.18s ease;
        }

        input:focus,
        select:focus {
            border-color:var(--green-600);
            box-shadow:0 0 0 4px rgba(5,150,105,.12);
        }

        .hint {
            display:block;
            margin-top:7px;
            color:var(--slate-500);
            font-size:11px;
            line-height:1.45;
        }

        .type-options {
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:12px;
        }

        .type-option { position:relative; }
        .type-option input {
            position:absolute;
            opacity:0;
            pointer-events:none;
        }

        .type-option label {
            min-height:118px;
            margin:0;
            padding:18px;
            border:1px solid var(--slate-300);
            border-radius:14px;
            cursor:pointer;
            transition:.18s ease;
        }

        .type-option label strong {
            display:block;
            margin-bottom:7px;
            color:var(--slate-950);
            font-size:14px;
        }

        .type-option label span {
            color:var(--slate-500);
            font-size:12px;
            line-height:1.5;
        }

        .type-option input:checked + label {
            border-color:var(--green-600);
            background:var(--green-50);
            box-shadow:0 0 0 3px rgba(5,150,105,.11);
        }

        .privacy {
            margin:22px 0;
            padding:14px 16px;
            display:flex;
            gap:12px;
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

        .lock {
            flex:0 0 auto;
            width:30px;
            height:30px;
            display:grid;
            place-items:center;
            border-radius:9px;
            background:#fff;
            color:var(--green-800);
            font-weight:950;
        }

        .submit {
            width:100%;
            min-height:50px;
            border:0;
            border-radius:12px;
            background:linear-gradient(145deg,var(--green-600),var(--green-800));
            color:#fff;
            font-weight:900;
            cursor:pointer;
            box-shadow:0 13px 30px rgba(4,120,87,.2);
        }

        .submit:hover { filter:brightness(.98); }

        @media (max-width:820px) {
            .page { grid-template-columns:1fr; }
            .intro { position:static; }
        }

        @media (max-width:570px) {
            .page { margin-top:26px; }
            .intro,.form-card { padding:24px; border-radius:19px; }
            .grid,.type-options { grid-template-columns:1fr; }
            .field.full { grid-column:auto; }
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
            <a class="back" href="{{ route('home') }}">
                ← Kembali ke Beranda
            </a>
        </nav>
    </header>

    <main class="page">
        <aside class="intro">
            <p class="eyebrow">Form konsultasi</p>
            <h1>Sampaikan kebutuhan Anda dengan jelas.</h1>
            <p>
                Informasi berikut membantu admin apotek memahami konteks awal
                sebelum percakapan dilanjutkan melalui ruang chat privat.
            </p>

            <div class="steps">
                <div class="step">
                    <span>1</span>
                    <div>
                        <strong>Lengkapi data dasar</strong>
                        <small>Pastikan nama, umur, dan nomor kontak benar.</small>
                    </div>
                </div>
                <div class="step">
                    <span>2</span>
                    <div>
                        <strong>Pilih jenis konsultasi</strong>
                        <small>Tentukan apakah berkaitan dengan resep dokter.</small>
                    </div>
                </div>
                <div class="step">
                    <span>3</span>
                    <div>
                        <strong>Lanjutkan ke chat</strong>
                        <small>Ruang chat dibuat otomatis setelah form dikirim.</small>
                    </div>
                </div>
            </div>
        </aside>

        <section class="form-card">
            <header class="form-head">
                <h2>Data Konsultasi</h2>
                <p>Kolom bertanda wajib harus diisi sebelum melanjutkan.</p>
            </header>

            @if ($errors->any())
                <div class="error-box">
                    <strong>Periksa kembali data berikut:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('consultation.store') }}"
                method="POST"
            >
                @csrf

                <div class="grid">
                    <div class="field full">
                        <label for="nama">Nama pasien</label>
                        <input
                            id="nama"
                            type="text"
                            name="nama"
                            value="{{ old('nama') }}"
                            maxlength="100"
                            autocomplete="name"
                            placeholder="Masukkan nama lengkap"
                            required
                            autofocus
                        >
                    </div>

                    <div class="field">
                        <label for="umur">Umur</label>
                        <input
                            id="umur"
                            type="number"
                            name="umur"
                            value="{{ old('umur') }}"
                            min="1"
                            max="120"
                            inputmode="numeric"
                            placeholder="Contoh: 25"
                            required
                        >
                        <span class="hint">Masukkan umur dalam tahun.</span>
                    </div>

                    <div class="field">
                        <label for="no_hp">Nomor HP</label>
                        <input
                            id="no_hp"
                            type="tel"
                            name="no_hp"
                            value="{{ old('no_hp') }}"
                            maxlength="25"
                            autocomplete="tel"
                            placeholder="Contoh: 081234567890"
                            required
                        >
                        <span class="hint">Digunakan sebagai data kontak konsultasi.</span>
                    </div>

                    <div class="field full">
                        <label>Jenis konsultasi</label>
                        <div class="type-options">
                            <div class="type-option">
                                <input
                                    id="type_resep"
                                    type="radio"
                                    name="jenis_konsultasi"
                                    value="resep"
                                    @checked(old('jenis_konsultasi') === 'resep')
                                    required
                                >
                                <label for="type_resep">
                                    <strong>Resep Dokter</strong>
                                    <span>
                                        Konsultasi terkait resep, aturan pakai,
                                        atau informasi obat dari dokter.
                                    </span>
                                </label>
                            </div>

                            <div class="type-option">
                                <input
                                    id="type_non_resep"
                                    type="radio"
                                    name="jenis_konsultasi"
                                    value="non_resep"
                                    @checked(old('jenis_konsultasi') === 'non_resep')
                                    required
                                >
                                <label for="type_non_resep">
                                    <strong>Non Resep</strong>
                                    <span>
                                        Pertanyaan umum mengenai obat bebas
                                        dan kebutuhan farmasi lainnya.
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="privacy">
                    <span class="lock">✓</span>
                    <div>
                        <strong>Akses konsultasi bersifat privat</strong>
                        Ruang chat terikat pada sesi browser ini. Jangan
                        membagikan perangkat atau alamat chat kepada pihak lain.
                    </div>
                </div>

                <button class="submit" type="submit">
                    Buat Konsultasi dan Lanjut ke Chat
                </button>
            </form>
        </section>
    </main>
</body>
</html>
