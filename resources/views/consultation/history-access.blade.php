<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>
        {{ $mode === 'setup' ? 'Buat Password Riwayat' : 'Buka Riwayat Konsultasi' }}
        — MD Farma
    </title>

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
                radial-gradient(circle at 88% 5%,rgba(16,185,129,.18),transparent 25%),
                #f8fafc;
        }

        .topbar {
            border-bottom:1px solid rgba(203,213,225,.72);
            background:rgba(255,255,255,.9);
            backdrop-filter:blur(14px);
        }

        nav {
            width:min(920px,92%);
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
            width:min(620px,92%);
            margin:54px auto 70px;
        }

        .access-card {
            overflow:hidden;
            border:1px solid var(--slate-200);
            border-radius:25px;
            background:#fff;
            box-shadow:0 24px 70px rgba(15,23,42,.1);
        }

        .access-head {
            padding:30px;
            color:#fff;
            background:
                radial-gradient(circle at 100% 0%,rgba(255,255,255,.14),transparent 38%),
                linear-gradient(145deg,var(--green-800),var(--green-950));
        }

        .eyebrow {
            margin:0 0 9px;
            color:#a7f3d0;
            font-size:11px;
            font-weight:900;
            letter-spacing:.08em;
            text-transform:uppercase;
        }

        h1 {
            margin:0;
            font-size:clamp(27px,5vw,37px);
            line-height:1.08;
            letter-spacing:-.04em;
        }

        .access-head p:last-child {
            margin:13px 0 0;
            color:#d1fae5;
            font-size:13px;
            line-height:1.65;
        }

        .content {
            padding:28px 30px 30px;
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
            border:1px solid #a7f3d0;
            background:var(--green-50);
            color:var(--green-900);
        }

        .notice.warning {
            border-color:#fde68a;
            background:#fffbeb;
            color:#92400e;
        }

        .error-box {
            border:1px solid #fecaca;
            background:#fef2f2;
            color:#991b1b;
        }

        .error-box ul {
            margin:0;
            padding-left:18px;
        }

        .field + .field { margin-top:16px; }

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
            .access-head,.content { padding:24px 22px; }
            .access-card { border-radius:20px; }
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
        <section class="access-card" aria-labelledby="access-title">
            <header class="access-head">
                <p class="eyebrow">
                    {{ $mode === 'setup' ? 'Pembaruan keamanan' : 'Akses konsultasi' }}
                </p>
                <h1 id="access-title">
                    {{ $mode === 'setup' ? 'Buat Password Riwayat.' : 'Buka riwayat konsultasi.' }}
                </h1>
                <p>
                    @if ($mode === 'setup')
                        Browser ini memiliki konsultasi lama yang belum dilindungi
                        password. Buat password sebelum melanjutkan.
                    @else
                        Perangkat ini dikenali. Masukkan password untuk membuka
                        chat dan riwayat konsultasi yang terhubung.
                    @endif
                </p>
            </header>

            <div class="content">
                @if (session('status'))
                    <div class="notice">{{ session('status') }}</div>
                @endif

                @if (session('warning'))
                    <div class="notice warning">{{ session('warning') }}</div>
                @endif

                @if ($errors->any())
                    <div class="error-box">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    action="{{ $mode === 'setup'
                        ? route('consultation.history.setup')
                        : route('consultation.history.unlock') }}"
                    method="POST"
                >
                    @csrf

                    <div class="field">
                        <label for="password_riwayat">
                            {{ $mode === 'setup' ? 'Buat Password Riwayat' : 'Password Riwayat' }}
                        </label>
                        <input
                            id="password_riwayat"
                            type="password"
                            name="password_riwayat"
                            minlength="{{ $mode === 'setup'
                                ? max(8, (int) config('consultation.history_password_min_length', 10))
                                : 1 }}"
                            maxlength="128"
                            autocomplete="{{ $mode === 'setup' ? 'new-password' : 'current-password' }}"
                            placeholder="{{ $mode === 'setup'
                                ? 'Minimal '.max(8, (int) config('consultation.history_password_min_length', 10)).' karakter'
                                : 'Masukkan password Anda' }}"
                            required
                            autofocus
                        >
                        <span class="hint">
                            @if ($mode === 'setup')
                                Gunakan password yang mudah Anda ingat tetapi sulit ditebak.
                            @else
                                Setelah {{ max(3, (int) config('consultation.history_password_max_attempts', 5)) }} percobaan gagal,
                                akses dikunci sementara selama
                                {{ max(5, (int) config('consultation.history_password_lock_minutes', 15)) }} menit.
                            @endif
                        </span>
                    </div>

                    @if ($mode === 'setup')
                        <div class="field">
                            <label for="password_riwayat_confirmation">
                                Konfirmasi password
                            </label>
                            <input
                                id="password_riwayat_confirmation"
                                type="password"
                                name="password_riwayat_confirmation"
                                minlength="{{ max(8, (int) config('consultation.history_password_min_length', 10)) }}"
                                maxlength="128"
                                autocomplete="new-password"
                                placeholder="Ketik ulang password"
                                required
                            >
                        </div>
                    @endif

                    <button class="submit" type="submit">
                        {{ $mode === 'setup'
                            ? 'Simpan Password dan Lanjutkan'
                            : 'Buka Riwayat Konsultasi' }}
                    </button>
                </form>

                <div class="privacy">
                    <strong>Password tidak dapat dilihat oleh admin</strong>
                    Password disimpan dalam bentuk hash. Pada tahap ini,
                    pemulihan ketika cookie hilang atau perangkat diganti belum tersedia.
                </div>
            </div>
        </section>
    </main>
</body>
</html>
