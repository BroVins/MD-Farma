<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Login Admin — MD Farma</title>

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
        }

        * { box-sizing:border-box; }

        body {
            min-height:100vh;
            margin:0;
            display:grid;
            place-items:center;
            padding:30px;
            font-family:Inter,ui-sans-serif,system-ui,-apple-system,
                BlinkMacSystemFont,"Segoe UI",sans-serif;
            color:var(--slate-950);
            background:
                radial-gradient(circle at 15% 10%,rgba(16,185,129,.22),transparent 25%),
                radial-gradient(circle at 90% 90%,rgba(5,150,105,.15),transparent 25%),
                #f8fafc;
        }

        .shell {
            width:min(920px,100%);
            display:grid;
            grid-template-columns:1fr 1fr;
            overflow:hidden;
            border:1px solid rgba(203,213,225,.8);
            border-radius:28px;
            background:#fff;
            box-shadow:0 30px 90px rgba(15,23,42,.14);
        }

        .intro {
            min-height:570px;
            padding:48px;
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            color:#fff;
            background:
                radial-gradient(circle at 100% 0%,rgba(255,255,255,.14),transparent 34%),
                linear-gradient(145deg,var(--green-800),var(--green-950));
        }

        .brand {
            display:flex;
            align-items:center;
            gap:11px;
            color:#fff;
            text-decoration:none;
            font-weight:900;
        }

        .brand-mark {
            width:39px;
            height:39px;
            display:grid;
            place-items:center;
            border-radius:12px;
            background:rgba(255,255,255,.14);
            font-size:22px;
        }

        .intro-copy h1 {
            margin:0 0 16px;
            font-size:43px;
            line-height:1.04;
            letter-spacing:-.045em;
        }

        .intro-copy p {
            margin:0;
            color:#d1fae5;
            line-height:1.7;
            font-size:14px;
        }

        .security {
            display:flex;
            align-items:center;
            gap:9px;
            color:#a7f3d0;
            font-size:11px;
            font-weight:800;
        }

        .security::before {
            content:"✓";
            width:23px;
            height:23px;
            display:grid;
            place-items:center;
            border-radius:8px;
            background:rgba(255,255,255,.13);
        }

        .login {
            padding:48px;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .login h2 {
            margin:0 0 7px;
            font-size:30px;
            letter-spacing:-.035em;
        }

        .login > p {
            margin:0 0 28px;
            color:var(--slate-500);
            font-size:13px;
            line-height:1.55;
        }

        .alert {
            margin-bottom:17px;
            padding:12px 14px;
            border-radius:10px;
            font-size:12px;
        }

        .alert.error {
            border:1px solid #fecaca;
            background:#fef2f2;
            color:#991b1b;
        }

        .alert.success {
            border:1px solid #bbf7d0;
            background:#f0fdf4;
            color:#166534;
        }

        label {
            display:block;
            margin:0 0 8px;
            color:var(--slate-700);
            font-size:12px;
            font-weight:900;
        }

        input {
            width:100%;
            min-height:48px;
            margin-bottom:18px;
            padding:0 13px;
            border:1px solid var(--slate-300);
            border-radius:10px;
            outline:none;
            font:inherit;
            font-size:14px;
        }

        input:focus {
            border-color:var(--green-600);
            box-shadow:0 0 0 4px rgba(5,150,105,.12);
        }

        button {
            width:100%;
            min-height:49px;
            border:0;
            border-radius:11px;
            background:linear-gradient(145deg,var(--green-600),var(--green-800));
            color:#fff;
            font-weight:900;
            cursor:pointer;
            box-shadow:0 12px 27px rgba(4,120,87,.2);
        }

        .home-link {
            display:block;
            margin-top:19px;
            color:var(--slate-500);
            text-align:center;
            text-decoration:none;
            font-size:12px;
            font-weight:800;
        }

        @media (max-width:760px) {
            body { padding:18px; }
            .shell { grid-template-columns:1fr; }
            .intro { min-height:auto; padding:30px; gap:46px; }
            .intro-copy h1 { font-size:34px; }
            .login { padding:30px; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="intro">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark">+</span>
                <span>MD Farma</span>
            </a>

            <div class="intro-copy">
                <h1>Dashboard operasional apotek.</h1>
                <p>
                    Kelola konsultasi, balas pesan realtime, dan pantau
                    analitik layanan berdasarkan hari, bulan, tahun, serta
                    jam tersibuk.
                </p>
            </div>

            <span class="security">
                Area ini hanya untuk administrator terotorisasi
            </span>
        </section>

        <section class="login">
            <h2>Login Admin</h2>
            <p>Masukkan kredensial administrator untuk melanjutkan.</p>

            @if (session('error'))
                <div class="alert error">{{ session('error') }}</div>
            @endif

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form
                action="{{ route('admin.authenticate') }}"
                method="POST"
            >
                @csrf

                <label for="username">Username</label>
                <input
                    id="username"
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    autocomplete="username"
                    required
                    autofocus
                >

                <label for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    required
                >

                <button type="submit">Masuk ke Dashboard</button>
            </form>

            <a class="home-link" href="{{ route('home') }}">
                ← Kembali ke website utama
            </a>
        </section>
    </main>
</body>
</html>
